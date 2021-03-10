<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VolRepository::class)
 */
class Vol
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private $idV;
    /**
         * @Assert\Type("string")
     * @ORM\Column(type="string", length=255)
     */
    private $aeroD;

    /**
     * @Assert\Type("string")
     * @ORM\Column(type="string", length=255)
     */
    private $aeroA;

    /**
     * @ORM\Column(type="date")
     */
    private $dateD;

    /**
     * @ORM\Column(type="date")
     */
    private $dateA;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="vol",cascade={"all"},orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\Column(type="integer")
     */
    private $placeD;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdV(): ?int
    {
        return $this->idV;
    }

    public function getplace(): ?int
    {
        return $this->idV;
    }

    public function setplace(int $Place): self
    {
        $this->Place = $Place;

        return $this;
    }

    public function setIdV(int $idV): self
    {
        $this->idV = $idV;

        return $this;
    }

    public function getAeroD(): ?string
    {
        return $this->aeroD;
    }

    public function setAeroD(string $aeroD): self
    {
        $this->aeroD = $aeroD;

        return $this;
    }

    public function getAeroA(): ?string
    {
        return $this->aeroA;
    }

    public function setAeroA(string $aeroA): self
    {
        $this->aeroA = $aeroA;

        return $this;
    }

    public function getDateD(): ?\DateTimeInterface
    {
        return $this->dateD;
    }

    public function setDateD(\DateTimeInterface $dateD): self
    {
        $this->dateD = $dateD;

        return $this;
    }

    public function getDateA(): ?\DateTimeInterface
    {
        return $this->dateA;
    }

    public function setDateA(\DateTimeInterface $dateA): self
    {
        $this->dateA = $dateA;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setVol($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVol() === $this) {
                $reservation->setVol(null);
            }
        }

        return $this;
    }
    /**
     * Transform to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }

    public function getPlaceD(): ?int
    {
        return $this->placeD;
    }

    public function setPlaceD(int $placeD): self
    {
        $this->placeD = $placeD;

        return $this;
    }

}
