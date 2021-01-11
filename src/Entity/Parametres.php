<?php

namespace App\Entity;

use App\Repository\ParametresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParametresRepository::class)
 */
class Parametres
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
    private $clee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClee(): ?string
    {
        return $this->clee;
    }

    public function setClee(string $clee): self
    {
        $this->clee = $clee;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }
}
