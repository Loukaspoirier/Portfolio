<?php

namespace App\Entity;

use App\Repository\AboutRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AboutRepository::class)]
class About
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private string $title;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private string $work;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private string $timeWork;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private string $company;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private string $city;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message:"La date n'est pas valide.")]
    private $firstDate;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message:"La date n'est pas valide.")]
    private $lastDate;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getWork(): string
    {
        return $this->work;
    }

    public function setWork(string $work): self
    {
        $this->work = $work;

        return $this;
    }

    public function getTimeWork(): string
    {
        return $this->timeWork;
    }

    public function setTimeWork(string $timeWork): self
    {
        $this->timeWork = $timeWork;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLastDate()
    {
        return $this->lastDate;
    }

    public function setLastDate($lastDate): self
    {
        $this->lastDate = $lastDate;

        return $this;
    }

    public function getFirstDate()
    {
        return $this->firstDate;
    }

    public function setFirstDate($firstDate): self
    {
        $this->firstDate = $firstDate;

        return $this;
    }
}
