<?php

namespace App\Entity;

use App\Repository\AuthorisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuthorisationRepository::class)
 */
class Authorisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Lecture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Ecriture;

    /**
     * @ORM\OneToMany(targetEntity=Acces::class, mappedBy="Auto")
     */
    private $acces;

    public function __construct()
    {
        $this->acces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLecture(): ?bool
    {
        return $this->Lecture;
    }

    public function setLecture(bool $Lecture): self
    {
        $this->Lecture = $Lecture;

        return $this;
    }

    public function getEcriture(): ?bool
    {
        return $this->Ecriture;
    }

    public function setEcriture(bool $Ecriture): self
    {
        $this->Ecriture = $Ecriture;

        return $this;
    }

    /**
     * @return Collection|Acces[]
     */
    public function getAcces(): Collection
    {
        return $this->acces;
    }

    public function addAcce(Acces $acce): self
    {
        if (!$this->acces->contains($acce)) {
            $this->acces[] = $acce;
            $acce->setAuto($this);
        }

        return $this;
    }

    public function removeAcce(Acces $acce): self
    {
        if ($this->acces->removeElement($acce)) {
            // set the owning side to null (unless already changed)
            if ($acce->getAuto() === $this) {
                $acce->setAuto(null);
            }
        }

        return $this;
    }
}
