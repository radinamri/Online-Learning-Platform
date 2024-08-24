<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Enrollments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnrollmentController extends AbstractController
{
    #[Route('/enrollment/{id}', name: 'app_enrollment')]
    public function enroll(Request $request, Courses $course, EntityManagerInterface $entityManager): Response
    {
        // Check if the user is authenticated
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_home'); // Redirect if not logged in
        }

        // Create a new enrollment
        $enrollment = new Enrollments();
        $enrollment->setCourseID($course);
        $enrollment->addStudentID($user); // Assuming the user entity is a Student
        $enrollment->setEnrollmentDate(new \DateTimeImmutable());

        // Persist the enrollment to the database
        $entityManager->persist($enrollment);
        $entityManager->flush();

        // Add a flash message for success
        $this->addFlash('success', sprintf('You have successfully enrolled in the course: %s.', $course->getTitle()));

        return $this->redirectToRoute('app_course');
    }

    #[Route('/enrollment', name: 'app_enrollment_index')]
    public function index(): Response
    {
        return $this->render('enrollment/index.html.twig', [
            'controller_name' => 'EnrollmentController',
        ]);
    }
}
