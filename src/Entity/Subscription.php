<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{

    public const SUBSCRIPTION = [
            [
                'id'           => 'a87c6',
                'duration'     => 30,
                'regularPrice' => 20,
                'salePrice'    => 0
            ],
            [
                'id'           => 'c8e4s',
                'duration'     => 90,
                'regularPrice' => 60,
                'salePrice'    => 55
            ],
            [
                'id'           => 'f15w6',
                'duration'     => 180,
                'regularPrice' => 120,
                'salePrice'    => 105
            ],
            [
                'id'           => 'e56c4',
                'duration'     => 365,
                'regularPrice' => 240,
                'salePrice'    => 200
            ],
        ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_date;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $payment_type;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public static $PAYMENT_TYPE = array("manual"=>0);

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getPaymentType(): ?int
    {
        return $this->payment_type;
    }

    public function setPaymentType(int $payment_type): self
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(string $invoice): self
    {
        $this->invoice = $invoice;

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
}
