<?php

namespace App\Entity;

use App\Repository\EnrollmentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnrollmentsRepository::class)]
class Enrollments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\ManyToMany(targetEntity: Students::class)]
    private Collection $studentID;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Courses $courseID = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $enrollmentDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completionDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $grade = null;

    public function __construct()
    {
        $this->studentID = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudentID(): Collection
    {
        return $this->studentID;
    }

    public function addStudentID(Students $studentID): static
    {
        if (!$this->studentID->contains($studentID)) {
            $this->studentID->add($studentID);
        }

        return $this;
    }

    public function removeStudentID(Students $studentID): static
    {
        $this->studentID->removeElement($studentID);

        return $this;
    }

    public function getCourseID(): ?Courses
    {
        return $this->courseID;
    }

    public function setCourseID(?Courses $courseID): static
    {
        $this->courseID = $courseID;

        return $this;
    }

    public function getEnrollmentDate(): ?\DateTimeImmutable
    {
        return $this->enrollmentDate;
    }

    public function setEnrollmentDate(\DateTimeImmutable $enrollmentDate): static
    {
        $this->enrollmentDate = $enrollmentDate;

        return $this;
    }

    public function getCompletionDate(): ?\DateTimeImmutable
    {
        return $this->completionDate;
    }

    public function setCompletionDate(\DateTimeImmutable $completionDate): static
    {
        $this->completionDate = $completionDate;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): static
    {
        $this->grade = $grade;

        return $this;
    }
}
