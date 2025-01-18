<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project {

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $brochureFilename = "";

    #[ORM\Column(type: "string")]

    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(min: 5, max: 70, minMessage: "Le nom est trop court", maxMessage: "Le nom est trop long")]
    private string $name;

    #[ORM\Column(type: "string",nullable: true)]
    private ?string $link = null;

    #[ORM\Column(type: "text")]

    #[Assert\NotBlank(message:"La descripition ne peut pas être vide.")]
    #[Assert\Length(min: 10, max: 300, minMessage: "La description doit faire plus de 10 caractères.", maxMessage: "La description doit faire moins de 300 caractères.")]
    private string $description;

    #[ORM\Column(type: "date")]

    #[Assert\NotBlank(message:"La date n'est pas valide.")]
    private $date;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'projects')]
    private Collection $skills;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getBrochureFilename(): string
    {
        return $this->brochureFilename;
    }

    public function setBrochureFilename(string $brochureFilename): self
    {
        $this->brochureFilename = $brochureFilename;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }
}