<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'payment')]
    private ?Contribution $contribution = null;

    #[ORM\Column(length: 255)]
    private ?string $cardOwner = null;

    #[ORM\Column(length: 255)]
    private ?string $cardNumbers = null;

    #[ORM\Column(length: 255)]
    private ?string $cardExpirationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $cardCode = null;

    #[ORM\Column(length: 255)]
    private ?string $cardType = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now',new \DateTimeZone("Europe/Paris"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getContribution(): ?Contribution
    {
        return $this->contribution;
    }

    public function setContribution(?Contribution $contribution): self
    {
        // unset the owning side of the relation if necessary
        if ($contribution === null && $this->contribution !== null) {
            $this->contribution->setPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($contribution !== null && $contribution->getPayment() !== $this) {
            $contribution->setPayment($this);
        }

        $this->contribution = $contribution;

        return $this;
    }

    public function getCardOwner(): ?string
    {
        return $this->cardOwner;
    }

    public function setCardOwner(string $cardOwner): self
    {
        $this->cardOwner = $cardOwner;

        return $this;
    }

    public function getCardNumbers(): ?string
    {
        return $this->cardNumbers;
    }

    public function setCardNumbers(string $cardNumbers): self
    {
        $this->cardNumbers = $cardNumbers;

        return $this;
    }

    public function getCardExpirationDate(): ?string
    {
        return $this->cardExpirationDate;
    }

    public function setCardExpirationDate(?string $cardExpirationDate): void
    {
        $this->cardExpirationDate = $cardExpirationDate;
    }

    public function getCardCode(): ?string
    {
        return $this->cardCode;
    }

    public function setCardCode(string $cardCode): self
    {
        $this->cardCode = $cardCode;

        return $this;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(string $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }
}
