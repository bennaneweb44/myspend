<?php

namespace App\Entity;

use App\Repository\CategorieAlimentationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieAlimentationRepository::class)
 */
class CategorieAlimentation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icone;

    /**
     * @ORM\OneToMany(targetEntity=Alimentation::class, mappedBy="categorie")
     */
    private $alimentations;

    public function __construct()
    {
        $this->alimentations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): self
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * @return Collection|Alimentation[]
     */
    public function getAlimentations(): Collection
    {
        return $this->alimentations;
    }

    public function addAlimentation(Alimentation $alimentation): self
    {
        if (!$this->alimentations->contains($alimentation)) {
            $this->alimentations[] = $alimentation;
            $alimentation->setCategorie($this);
        }

        return $this;
    }

    public function removeAlimentation(Alimentation $alimentation): self
    {
        if ($this->alimentations->removeElement($alimentation)) {
            // set the owning side to null (unless already changed)
            if ($alimentation->getCategorie() === $this) {
                $alimentation->setCategorie(null);
            }
        }

        return $this;
    }
}
