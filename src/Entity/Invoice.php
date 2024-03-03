<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    use Traits\Timestampable;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

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

    #[ORM\Column(type: Types::DATE_MUTABLE, options: ["default" => "CURRENT_DATE"])]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Quotation $quotations = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'invoices', cascade: ["persist"])]
    private Collection $services;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private ?bool $isPaid = false;

    #[ORM\Column]
    private ?float $vatRate10 = 0;

    #[ORM\Column]
    private ?float $vatRate20 = 0;

    #[ORM\Column]
    private ?float $baseVatRate10 = 0;

    #[ORM\Column]
    private ?float $baseVatRate20 = 0;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

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
    
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getQuotations(): ?Quotation
    {
        return $this->quotations;
    }

    public function setQuotations(?Quotation $quotations): static
    {
        $this->quotations = $quotations;

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

    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

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

    public function getBaseVatRate20(): ?float
    {
        return $this->baseVatRate20;
    }

    public function setBaseVatRate20(float $baseVatRate20): static
    {
        $this->baseVatRate20 = $baseVatRate20;

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
