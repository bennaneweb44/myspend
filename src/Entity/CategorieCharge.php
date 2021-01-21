<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieChargeRepository::class)
 */
class CategorieCharge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("CategorieCharge:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("CategorieCharge:read")
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("CategorieCharge:read")
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("CategorieCharge:read")
     */
    private $icone;

    /**
     * @ORM\OneToMany(targetEntity=Charges::class, mappedBy="categorie")
     */
    private $charges;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups("CategorieCharge:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups("CategorieCharge:read")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("CategorieCharge:read")
     */
    private $deletedAt;

    public function __construct()
    {
        $this->charges = new ArrayCollection();
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


    public function addCharge(Charges $charge): self
    {
        if (!$this->charges->contains($charge)) {
            $this->charges[] = $charge;
            $charge->setCategorie($this);
        }

        return $this;
    }

    public function removeCharge(Charges $charge): self
    {
        if ($this->charges->removeElement($charge)) {
            // set the owning side to null (unless already changed)
            if ($charge->getCategorie() === $this) {
                $charge->setCategorie(null);
            }
        }

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
