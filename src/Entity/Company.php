<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commercial_name = null;

    #[ORM\Column(nullable: true, unique: true)]
    private ?int $siren = null;

    #[ORM\Column(nullable: true)]
    private ?int $tva_number = null;

    #[ORM\Column(nullable: true)]
    private ?int $share_capital = null;

    #[ORM\Column(nullable: true)]
    private ?int $address = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $bank_statement = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Quotation::class, orphanRemoval: true)]
    private Collection $quotations;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Invoice::class, orphanRemoval: true)]
    private Collection $invoices;

    public function __construct()
    {
        $this->quotations = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommercialName(): ?string
    {
        return $this->commercial_name;
    }

    public function setCommercialName(?string $commercial_name): static
    {
        $this->commercial_name = $commercial_name;

        return $this;
    }

    public function getSiren(): ?int
    {
        return $this->siren;
    }

    public function setSiren(?int $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getTvaNumber(): ?int
    {
        return $this->tva_number;
    }

    public function setTvaNumber(?int $tva_number): static
    {
        $this->tva_number = $tva_number;

        return $this;
    }

    public function getShareCapital(): ?int
    {
        return $this->share_capital;
    }

    public function setShareCapital(?int $share_capital): static
    {
        $this->share_capital = $share_capital;

        return $this;
    }

    public function getAddress(): ?int
    {
        return $this->address;
    }

    public function setAddress(?int $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(?\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getBankStatement(): ?int
    {
        return $this->bank_statement;
    }

    public function setBankStatement(?int $bank_statement): static
    {
        $this->bank_statement = $bank_statement;

        return $this;
    }

    /**
     * @return Collection<int, Quotation>
     */
    public function getQuotations(): Collection
    {
        return $this->quotations;
    }

    public function addQuotation(Quotation $quotation): static
    {
        if (!$this->quotations->contains($quotation)) {
            $this->quotations->add($quotation);
            $quotation->setCompany($this);
        }

        return $this;
    }

    public function removeQuotation(Quotation $quotation): static
    {
        if ($this->quotations->removeElement($quotation)) {
            // set the owning side to null (unless already changed)
            if ($quotation->getCompany() === $this) {
                $quotation->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setCompany($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getCompany() === $this) {
                $invoice->setCompany(null);
            }
        }

        return $this;
    }
}
