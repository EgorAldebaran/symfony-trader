<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $unitname = null;

    #[ORM\Column]
    private ?float $power = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnitname(): ?string
    {
        return $this->unitname;
    }

    public function setUnitname(string $unitname): static
    {
        $this->unitname = $unitname;

        return $this;
    }

    public function getPower(): ?float
    {
        return $this->power;
    }

    public function setPower(float $power): static
    {
        $this->power = $power;

        return $this;
    }
}
