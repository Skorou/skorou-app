<?php

namespace App\Entity;

use App\Repository\CreditCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CreditCardRepository::class)
 */
class CreditCard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $number;

    /**
     * @ORM\Column(type="integer")
     */
    private $expiry_month;

    /**
     * @ORM\Column(type="integer")
     */
    private $expiry_year;

    /**
     * @ORM\Column(type="integer")
     */
    private $cvv;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_favorite;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="credit_cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="credit_card")
     */
    private $subscriptions;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getExpiryMonth(): ?int
    {
        return $this->expiry_month;
    }

    public function setExpiryMonth(int $expiry_month): self
    {
        $this->expiry_month = $expiry_month;

        return $this;
    }

    public function getExpiryYear(): ?int
    {
        return $this->expiry_year;
    }

    public function setExpiryYear(int $expiry_year): self
    {
        $this->expiry_year = $expiry_year;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getIsFavorite(): ?bool
    {
        return $this->is_favorite;
    }

    public function setIsFavorite(bool $is_favorite): self
    {
        $this->is_favorite = $is_favorite;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setCreditCard($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getCreditCard() === $this) {
                $subscription->setCreditCard(null);
            }
        }

        return $this;
    }
}
