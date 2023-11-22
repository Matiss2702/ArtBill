<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vat_rate = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_deposit = null;

    #[ORM\Column(nullable: true)]
    private ?int $client = null;

    #[ORM\Column(nullable: true)]
    private ?int $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getVatRate(): ?string
    {
        return $this->vat_rate;
    }

    public function setVatRate(?string $vat_rate): static
    {
        $this->vat_rate = $vat_rate;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDue_Date(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDue_Date(\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function isIsDeposit(): ?bool
    {
        return $this->is_deposit;
    }

    public function setIsDeposit(?bool $is_deposit): static
    {
        $this->is_deposit = $is_deposit;

        return $this;
    }

    public function getClient(): ?int
    {
        return $this->client;
    }

    public function setClient(?int $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCompany(): ?int
    {
        return $this->company;
    }

    public function setCompany(?int $company): static
    {
        $this->company = $company;

        return $this;
    }
}
