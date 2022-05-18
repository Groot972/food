<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandesRepository::class)
 */
class Commandes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Adresse::class, inversedBy="commandes")
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=LigneDeCommande::class, mappedBy="commandes")
     */
    private $ldc;

    public function __construct()
    {
        $this->ldc = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, LigneDeCommande>
     */
    public function getLdc(): Collection
    {
        return $this->ldc;
    }

    public function addLdc(LigneDeCommande $ldc): self
    {
        if (!$this->ldc->contains($ldc)) {
            $this->ldc[] = $ldc;
            $ldc->setCommandes($this);
        }

        return $this;
    }

    public function removeLdc(LigneDeCommande $ldc): self
    {
        if ($this->ldc->removeElement($ldc)) {
            // set the owning side to null (unless already changed)
            if ($ldc->getCommandes() === $this) {
                $ldc->setCommandes(null);
            }
        }

        return $this;
    }
}
