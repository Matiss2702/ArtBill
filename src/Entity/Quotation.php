<?php

namespace App\Entity;

use App\Repository\QuotationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: QuotationRepository::class)]
class Quotation
{
    use Traits\Timestampable;
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public const QUOTATION_STATUS = [
        'created',
        'sent',
        'refused',
        'accepted',
        'paid',
        'expired',
        'archived',
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

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Customer $customer = null;

    #[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'quotations', cascade: ["persist"])]
    private Collection $services;

    #[ORM\Column]
    private ?int $version = null;

    #[ORM\OneToOne(inversedBy: 'nextQuotation', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $previousVersion = null;

    #[ORM\OneToOne(mappedBy: 'previousVersion', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $nextQuotation = null;

    #[ORM\OneToMany(mappedBy: 'quotations', targetEntity: Invoice::class)]
    private Collection $invoices;

    #[ORM\Column]
    private ?float $vatRate10 = 0;

    #[ORM\Column]
    private ?float $vatRate20 = 0;

    #[ORM\Column]
    private ?float $baseVatRate10 = 0;

    #[ORM\Column]
    private ?float $baseVatRate20 = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column]
    private ?float $amountHt = 0;

    #[ORM\Column]
    private ?float $amountTtc = 0;

    #[ORM\Column]
    private ?float $baseVatRate0 = 0;

    public function __construct()
    {
        $this->dueDate = (new DateTime())->modify('+30 days');
        $this->services = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
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
        return $this->previousVersion;
    }

    public function setPreviousVersion(?self $previousVersion): static
    {
        $this->previousVersion = $previousVersion;
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
        return $this->nextQuotation;
    }

    public function setNextQuotation(?self $nextQuotation): static
    {
        if ($nextQuotation === null && $this->nextQuotation !== null) {
            $this->nextQuotation->setPreviousVersion(null);
        }

        if ($nextQuotation !== null && $nextQuotation->getPreviousVersion() !== $this) {
            $nextQuotation->setPreviousVersion($this);
        }

        $this->nextQuotation = $nextQuotation;
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

    public function getVatRate10(): ?float
    {
        return $this->vatRate10;
    }

    public function setVatRate10(float $vatRate10): static
    {
        $this->vatRate10 = $vatRate10;

        return $this;
    }

    public function getVatRate20(): ?float
    {
        return $this->vatRate20;
    }

    public function setVatRate20(float $vatRate20): static
    {
        $this->vatRate20 = $vatRate20;

        return $this;
    }

    public function getBaseVatRate10(): ?float
    {
        return $this->baseVatRate10;
    }

    public function setBaseVatRate10(float $baseVatRate10): static
    {
        $this->baseVatRate10 = $baseVatRate10;

        return $this;
    }

    public function getBaseVatRate20(): ?float
    {
        return $this->baseVatRate20;
    }

    public function setBaseVatRate20(float $baseVatRate20): static
    {
        $this->baseVatRate20 = $baseVatRate20;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getAmountHt(): ?float
    {
        return $this->amountHt;
    }

    public function setAmountHt(float $amountHt): static
    {
        $this->amountHt = $amountHt;

        return $this;
    }

    public function getAmountTtc(): ?float
    {
        return $this->amountTtc;
    }

    public function setAmountTtc(float $amountTtc): static
    {
        $this->amountTtc = $amountTtc;

        return $this;
    }

    public function getBaseVatRate0(): ?float
    {
        return $this->baseVatRate0;
    }

    public function setBaseVatRate0(float $baseVatRate0): static
    {
        $this->baseVatRate0 = $baseVatRate0;

        return $this;
    }
}
