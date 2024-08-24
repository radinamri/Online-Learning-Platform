<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EnrollmentController extends AbstractController
{
    #[Route('/enrollment', name: 'app_enrollment')]
    public function index(): Response
    {
        return $this->render('enrollment/index.html.twig', [
            'controller_name' => 'EnrollmentController',
        ]);
    }
}
