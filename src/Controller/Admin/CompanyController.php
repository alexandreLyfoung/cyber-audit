<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/entreprises')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_admin_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('admin/company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_admin_company_show',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }

}
