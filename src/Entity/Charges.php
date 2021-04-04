<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChargesRepository::class)
 */
class Charges
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("charge:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("charge:read")
     */
    private $libelle;

    /**
     * @ORM\Column(type="float")
     * @Groups("charge:read")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieCharge::class, inversedBy="charges")     
     * @Groups("charge:read")
     */
    private $categorie;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups("charge:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups("charge:read")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("charge:read")
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("charge:read")
     */
    private $commentaires;

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

    public function getCategorie(): ?CategorieCharge
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieCharge $categorie): self
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

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }
}
