<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     *     value=0
     *     )
     */
    private $idR;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     *     value=0
     *     )
     * @Assert\Length(
     *     value=8
     *     )
     */
    private $cin;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThan(
     *     value=0
     *     )
     */
    private $prix;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateV;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThan(
     *     value=0
     *     )
     */
    private $numP;
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $email;


    /**
     * @ORM\ManyToOne(targetEntity=Vol::class, inversedBy="reservations")
     */
    private $vol;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdR(): ?int
    {
        return $this->idR;
    }

    public function setIdR(int $idR): self
    {
        $this->idR = $idR;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateV(): ?\DateTimeInterface
    {
        return $this->dateV;
    }

    public function setDateV(?\DateTimeInterface $dateV): self
    {
        $this->dateV = $dateV;

        return $this;
    }

    public function getNumP(): ?int
    {
        return $this->numP;
    }

    public function setNumP(?int $numP): self
    {
        $this->numP = $numP;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getVol(): ?Vol
    {
        return $this->vol;
    }

    public function setVol(?Vol $vol): self
    {
        $this->vol = $vol;

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
}
