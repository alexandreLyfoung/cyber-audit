<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Contribution;
use App\Entity\Payment;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\fr_FR\Address as FakerAddress;
use Faker\Provider\fr_FR\Company as FakerCompany;
use Faker\Provider\fr_FR\Person as FakerPerson;
use Faker\Provider\fr_FR\PhoneNumber as FakerPhoneNumber;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadContributions($manager);
    }

    private function buildFaker(): Generator
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new FakerAddress($faker));
        $faker->addProvider(new FakerPerson($faker));
        $faker->addProvider(new FakerPhoneNumber($faker));
        $faker->addProvider(new FakerCompany($faker));

        return $faker;
    }

    private function loadUsers(ObjectManager $manager)
    {
        $faker = $this->buildFaker();

        $users =
            [
                ["email" => "admin@mail.dev","roles" => ["ROLE_ADMIN"],"password"=>"password"],
                ["email" => "company@mail.dev","roles" => ["ROLE_COMPANY"],"password"=>"password"],
            ];

        for($i = 1; $i<= 10; $i++){
            $users[] = [
                "email"=>$faker->email(),
                "roles" => ["ROLE_COMPANY"],
                "password"=>"password$i"
            ];
        }

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data["email"]);
            $user->setRoles($data["roles"]);
            $user->setPlainPassword($data["password"]);
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
            $manager->persist($user);

            if($user->getRoles() === ["ROLE_COMPANY"]){
                $company = $this->makeCompany($faker);
                $user->setCompany($company);
                $manager->persist($company);
            }

        }
        $manager->flush();
    }

    private function makeCompany(Generator $faker): Company
    {
        $company = new Company();
        $company->setSiret($faker->siret(false));
        $company->setName($faker->company());
        $company->setAddress($faker->address());
        $company->setPhone($faker->phoneNumber());
        return $company;
    }

    private function loadContributions(ObjectManager $manager){
        $faker = $this->buildFaker();
        $companies = $manager->getRepository(Company::class)->findAll();

        foreach($companies as $c){

            $contribution = new Contribution();
            $contribution->setYear($_ENV['DECLARATION_YEAR'] -1);
            $contribution->setBase($faker->randomNumber(5, true));
            $contribution->calculate();
            $contribution->setCompany($c);
            if($faker->randomDigit() >= 3 ){
                $contribution->setPayment($this->makePayment($faker));
            }
            $manager->persist($contribution);
        }
        $manager->flush();
    }

    private function makePayment(Generator $faker): Payment
    {
        $payment = new Payment();
        $payment->setCardOwner($faker->name());
        $payment->setCardType($faker->creditCardType());
        $payment->setCardNumbers($faker->creditCardNumber($payment->getCardType()));
        $payment->setCardExpirationDate($faker->creditCardExpirationDateString());
        $payment->setCardCode($faker->randomNumber(3, true));

        return $payment;
    }


}
