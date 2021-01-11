<?php

namespace App\Entity;

use App\Repository\AlimentationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlimentationRepository::class)
 */
class Alimentation
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
    private $libelle;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieAlimentation::class, inversedBy="alimentations")
     */
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCategorie(): ?CategorieAlimentation
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieAlimentation $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
