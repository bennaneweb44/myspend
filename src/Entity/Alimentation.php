<?php

namespace App\Entity;

use App\Repository\AlimentationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AlimentationRepository::class)
 */
class Alimentation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("alimentation:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("alimentation:read")
     */
    private $libelle;

    /**
     * @ORM\Column(type="float")
     * @Groups("alimentation:read")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieAlimentation::class, inversedBy="alimentations")
     * @Groups("alimentation:read")
     */
    private $categorie;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups("alimentation:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups("alimentation:read")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("alimentation:read")
     */
    private $deletedAt;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
