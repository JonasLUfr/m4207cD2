<?php

namespace App\Entity;

use App\Repository\AccesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccesRepository::class)
 */
class Acces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $util;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Doc;

    /**
     * @ORM\ManyToOne(targetEntity=Authorisation::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Auto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtil(): ?Utilisateur
    {
        return $this->util;
    }

    public function setUtil(?Utilisateur $util): self
    {
        $this->util = $util;

        return $this;
    }

    public function getDoc(): ?Document
    {
        return $this->Doc;
    }

    public function setDoc(?Document $Doc): self
    {
        $this->Doc = $Doc;

        return $this;
    }

    public function getAuto(): ?Authorisation
    {
        return $this->Auto;
    }

    public function setAuto(?Authorisation $Auto): self
    {
        $this->Auto = $Auto;

        return $this;
    }
}
