<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $commercial_name = null;

    #[ORM\Column]
    private ?int $siren = null;

    #[ORM\Column(length: 20)]
    private ?string $tva_number = null;

    #[ORM\Column]
    private ?int $share_capital = null;

    #[ORM\Column(length: 100)]
    private ?string $address = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(length: 255)]
    private ?string $bank_statement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommercialName(): ?string
    {
        return $this->commercial_name;
    }

    public function setCommercialName(string $commercial_name): static
    {
        $this->commercial_name = $commercial_name;

        return $this;
    }

    public function getSiren(): ?int
    {
        return $this->siren;
    }

    public function setSiren(int $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getTvaNumber(): ?string
    {
        return $this->tva_number;
    }

    public function setTvaNumber(string $tva_number): static
    {
        $this->tva_number = $tva_number;

        return $this;
    }

    public function getShareCapital(): ?int
    {
        return $this->share_capital;
    }

    public function setShareCapital(int $share_capital): static
    {
        $this->share_capital = $share_capital;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getBankStatement(): ?string
    {
        return $this->bank_statement;
    }

    public function setBankStatement(string $bank_statement): static
    {
        $this->bank_statement = $bank_statement;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }
}
