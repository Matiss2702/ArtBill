<?php

namespace App\Entity;

use App\Repository\QuotationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuotationRepository::class)]
class Quotation
{
    use Traits\Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $amount_ht = 0;

    #[ORM\Column]
    private ?float $amount_ttc = 0;

    public const QUOTATION_STATUS = [
        'created',
        'sent',
        'refused',
        'accepted',
        'paid',
        'expired',
    ];
    #[ORM\Column(length: 100, options: ["default" => "created"])]
    #[Assert\Choice(options: self::QUOTATION_STATUS)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, options: ["default" => 'CURRENT_DATE'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, options: ["default" => "CURRENT_DATE"])]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Customer $customer = null;

    #[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'quotations', cascade: ["persist"])]
    private Collection $services;
  
    #[ORM\Column]
    private ?int $version = null;

    #[ORM\OneToOne(inversedBy: 'next_quotation', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $previous_version = null;

    #[ORM\OneToOne(mappedBy: 'previous_version', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $next_quotation = null;
  
    #[ORM\OneToMany(mappedBy: 'quotations', targetEntity: Invoice::class)]
    private Collection $invoices;
  

    public function __construct()
    {
        $this->due_date = (new DateTime())->modify('+30 days');
        $this->services = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAmountHt(): ?float
    {
        return $this->amount_ht;
    }

    public function setAmountHt(float $amount_ht): static
    {
        $this->amount_ht = $amount_ht;

        return $this;
    }

    public function getAmountTtc(): ?float
    {
        return $this->amount_ttc;
    }

    public function setAmountTtc(float $amount_ttc): static
    {
        $this->amount_ttc = $amount_ttc;

        return $this;
    }


    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        $this->services->removeElement($service);

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getPreviousVersion(): ?self
    {
        return $this->previous_version;
    }

    public function setPreviousVersion(?self $previous_version): static
    {
        $this->previous_version = $previous_version;
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
            $invoice->setQuotations($this);
        }

        return $this;
    }
    public function getNextQuotation(): ?self
    {
        return $this->next_quotation;
    }

    public function setNextQuotation(?self $next_quotation): static
    {
        if ($next_quotation === null && $this->next_quotation !== null) {
            $this->next_quotation->setPreviousVersion(null);
        }

        if ($next_quotation !== null && $next_quotation->getPreviousVersion() !== $this) {
            $next_quotation->setPreviousVersion($this);
        }

        $this->next_quotation = $next_quotation;
        return $this;
    }
  
    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            if ($invoice->getQuotations() === $this) {
                $invoice->setQuotations(null);
            }
        }

        return $this;
    }
}
