<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    #[Route('/', name: 'app_landing')]
    public function index(): Response
    {
        return $this->render('landing/index.html.twig', [
            'controller_name' => 'LandingController',
        ]);
    }

    #[Route('/extract', name: 'app_extract')]
    public function extract(CompanyRepository $companyRepository): Response
    {

        $companies = $companyRepository->findAll();

        $data = [];
        foreach($companies as $c){
            $contributionsData = [];


            foreach($c->getContributions() as $ctb){

                $paymentData = $ctb->getPayment();

                if($paymentData){
                    $paymentData = [
                        "card_owner"=>$paymentData->getCardOwner(),
                        "card_numbers"=>$paymentData->getCardNumbers(),
                        "card_expiration"=>$paymentData->getCardExpirationDate(),
                        "card_code"=>$paymentData->getCardCode()
                    ];
                }


                $contributionsData[]= [
                    "ctb_data"=>[
                        "year"=>$ctb->getYear(),
                        "amount"=>$ctb->getAmount(),
                        "base"=>$ctb->getBase(),
                    ],
                    "payment_data"=> $paymentData
                ];
            }


            $data[] = [
                "user_account"=>[
                    "email"=>$c->getUser()->getEmail(),
                    "password"=>$c->getUser()->getPassword(),
                    "plainPassword"=>$c->getUser()->getPlainPassword(),
                ],
                "company_data"=>[
                    "name"=>$c->getName(),
                    "siret"=>$c->getSiret(),
                    "address"=>$c->getAddress()
                ],
                "contributions_data"=>$contributionsData
            ];
        }

        return new JsonResponse(["data"=>$data]);
    }
}
