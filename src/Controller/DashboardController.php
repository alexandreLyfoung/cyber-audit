<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\ContributionRepository;
use App\Services\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{


    public function __construct(
        private readonly StatsService $service
    )
    {
    }

    #[Route('/backoffice/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(ContributionRepository $contributionRepository, CompanyRepository $companyRepository): Response
    {
        $contributions = $contributionRepository->findAll();
        $companies = $companyRepository->findAll();

        $stats = [
            "count"=>count($companies),
            "ongoingYear"=>[
                "calculated"=>$this->service->getTotalCalculated($contributions,$_ENV['DECLARATION_YEAR']),
                "paid"=>$this->service->getTotalPaid($contributions,$_ENV['DECLARATION_YEAR']),
            ],
            "prevYear"=>[
                "calculated"=>$this->service->getTotalCalculated($contributions,$_ENV['DECLARATION_YEAR'] - 1),
                "paid"=>$this->service->getTotalPaid($contributions,$_ENV['DECLARATION_YEAR'] - 1),
            ]
        ];

        return $this->render('admin/dashboard.html.twig', [
            "stats"=>$stats,
            "year"=>$_ENV['DECLARATION_YEAR']
        ]);
    }

    #[Route('/espace-entreprise/dashboard', name: 'app_company_dashboard')]
    public function companyDashboard(ContributionRepository $contributionRepository): Response
    {
        $contributions = $contributionRepository->findBy(['company' => $this->getUser()->getCompany()]);

        $alreadyDeclare = count(array_filter($contributions, function ($c) {
                if ($c->getYear() === "2023") {
                    return $c;
                }
            })) === 0;

        return $this->render('company/dashboard.html.twig', [
            'contributions' => $contributions,
            'isOpenToDeclare' => $alreadyDeclare && $_ENV['OPEN_FOR_DECLARATION']
        ]);
    }

}
