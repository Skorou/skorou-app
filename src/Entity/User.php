<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $password;

    /**
     * @ORM\Column(type="smallint")
     */
    private $account_type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $company_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company_picture;

    /**
     * @ORM\Column(type="integer")
     */
    private $free_creations;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity=CreditCard::class, mappedBy="user")
     */
    private $credit_cards;

    /**
     * @ORM\OneToMany(targetEntity=FontUser::class, mappedBy="user")
     */
    private $font_users;

    /**
     * @ORM\OneToMany(targetEntity=Logo::class, mappedBy="user")
     */
    private $logos;

    /**
     * @ORM\OneToMany(targetEntity=Color::class, mappedBy="user")
     */
    private $colors;

    /**
     * @ORM\OneToMany(targetEntity=ImageUploaded::class, mappedBy="user")
     */
    private $images_uploaded;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="user")
     */
    private $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity=Creation::class, mappedBy="user")
     */
    private $creations;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->credit_cards = new ArrayCollection();
        $this->font_users = new ArrayCollection();
        $this->logos = new ArrayCollection();
        $this->colors = new ArrayCollection();
        $this->images_uploaded = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->creations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAccountType(): ?int
    {
        return $this->account_type;
    }

    public function setAccountType(int $account_type): self
    {
        $this->account_type = $account_type;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getCompanyPicture(): ?string
    {
        return $this->company_picture;
    }

    public function setCompanyPicture(?string $company_picture): self
    {
        $this->company_picture = $company_picture;

        return $this;
    }

    public function getFreeCreations(): ?int
    {
        return $this->free_creations;
    }

    public function setFreeCreations(int $free_creations): self
    {
        $this->free_creations = $free_creations;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CreditCard[]
     */
    public function getCreditCards(): Collection
    {
        return $this->credit_cards;
    }

    public function addCreditCard(CreditCard $creditCard): self
    {
        if (!$this->credit_cards->contains($creditCard)) {
            $this->credit_cards[] = $creditCard;
            $creditCard->setUser($this);
        }

        return $this;
    }

    public function removeCreditCard(CreditCard $creditCard): self
    {
        if ($this->credit_cards->contains($creditCard)) {
            $this->credit_cards->removeElement($creditCard);
            // set the owning side to null (unless already changed)
            if ($creditCard->getUser() === $this) {
                $creditCard->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FontUser[]
     */
    public function getFontUsers(): Collection
    {
        return $this->font_users;
    }

    public function addFontUser(FontUser $fontUser): self
    {
        if (!$this->font_users->contains($fontUser)) {
            $this->font_users[] = $fontUser;
            $fontUser->setUser($this);
        }

        return $this;
    }

    public function removeFontUser(FontUser $fontUser): self
    {
        if ($this->font_users->contains($fontUser)) {
            $this->font_users->removeElement($fontUser);
            // set the owning side to null (unless already changed)
            if ($fontUser->getUser() === $this) {
                $fontUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Logo[]
     */
    public function getLogos(): Collection
    {
        return $this->logos;
    }

    public function addLogo(Logo $logo): self
    {
        if (!$this->logos->contains($logo)) {
            $this->logos[] = $logo;
            $logo->setUser($this);
        }

        return $this;
    }

    public function removeLogo(Logo $logo): self
    {
        if ($this->logos->contains($logo)) {
            $this->logos->removeElement($logo);
            // set the owning side to null (unless already changed)
            if ($logo->getUser() === $this) {
                $logo->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Color[]
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors[] = $color;
            $color->setUser($this);
        }

        return $this;
    }

    public function removeColors(Color $color): self
    {
        if ($this->colors->contains($color)) {
            $this->colors->removeElement($color);
            // set the owning side to null (unless already changed)
            if ($color->getUser() === $this) {
                $color->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ImageUploaded[]
     */
    public function getImagesUploaded(): Collection
    {
        return $this->images_uploaded;
    }

    public function addImageUploaded(ImageUploaded $imageUploaded): self
    {
        if (!$this->images_uploaded->contains($imageUploaded)) {
            $this->images_uploaded[] = $imageUploaded;
            $imageUploaded->setUser($this);
        }

        return $this;
    }

    public function removeImageUploaded(ImageUploaded $imageUploaded): self
    {
        if ($this->images_uploaded->contains($imageUploaded)) {
            $this->images_uploaded->removeElement($imageUploaded);
            // set the owning side to null (unless already changed)
            if ($imageUploaded->getUser() === $this) {
                $imageUploaded->setUser(null);
            }
        }

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
            $subscription->setUser($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getUser() === $this) {
                $subscription->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Creation[]
     */
    public function getCreations(): Collection
    {
        return $this->creations;
    }

    public function addCreation(Creation $creation): self
    {
        if (!$this->creations->contains($creation)) {
            $this->creations[] = $creation;
            $creation->setUser($this);
        }

        return $this;
    }

    public function removeCreation(Creation $creation): self
    {
        if ($this->creations->contains($creation)) {
            $this->creations->removeElement($creation);
            // set the owning side to null (unless already changed)
            if ($creation->getUser() === $this) {
                $creation->setUser(null);
            }
        }

        return $this;
    }
}
