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
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=CreditCard::class, mappedBy="user")
     */
    private $credit_card;

    /**
     * @ORM\OneToMany(targetEntity=FontUser::class, mappedBy="user")
     */
    private $font_user;

    /**
     * @ORM\OneToMany(targetEntity=Logo::class, mappedBy="user")
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Color::class, mappedBy="user")
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=ImageUploaded::class, mappedBy="user")
     */
    private $image_uploaded;

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
        $this->address = new ArrayCollection();
        $this->credit_card = new ArrayCollection();
        $this->font_user = new ArrayCollection();
        $this->logo = new ArrayCollection();
        $this->color = new ArrayCollection();
        $this->image_uploaded = new ArrayCollection();
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
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
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
    public function getCreditCard(): Collection
    {
        return $this->credit_card;
    }

    public function addCreditCard(CreditCard $creditCard): self
    {
        if (!$this->credit_card->contains($creditCard)) {
            $this->credit_card[] = $creditCard;
            $creditCard->setUser($this);
        }

        return $this;
    }

    public function removeCreditCard(CreditCard $creditCard): self
    {
        if ($this->credit_card->contains($creditCard)) {
            $this->credit_card->removeElement($creditCard);
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
    public function getFontUser(): Collection
    {
        return $this->font_user;
    }

    public function addFontUser(FontUser $fontUser): self
    {
        if (!$this->font_user->contains($fontUser)) {
            $this->font_user[] = $fontUser;
            $fontUser->setUser($this);
        }

        return $this;
    }

    public function removeFontUser(FontUser $fontUser): self
    {
        if ($this->font_user->contains($fontUser)) {
            $this->font_user->removeElement($fontUser);
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
    public function getLogo(): Collection
    {
        return $this->logo;
    }

    public function addLogo(Logo $logo): self
    {
        if (!$this->logo->contains($logo)) {
            $this->logo[] = $logo;
            $logo->setUser($this);
        }

        return $this;
    }

    public function removeLogo(Logo $logo): self
    {
        if ($this->logo->contains($logo)) {
            $this->logo->removeElement($logo);
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
    public function getColor(): Collection
    {
        return $this->color;
    }

    public function addColor(Color $color): self
    {
        if (!$this->color->contains($color)) {
            $this->color[] = $color;
            $color->setUser($this);
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        if ($this->color->contains($color)) {
            $this->color->removeElement($color);
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
    public function getImageUploaded(): Collection
    {
        return $this->image_uploaded;
    }

    public function addImageUploaded(ImageUploaded $imageUploaded): self
    {
        if (!$this->image_uploaded->contains($imageUploaded)) {
            $this->image_uploaded[] = $imageUploaded;
            $imageUploaded->setUser($this);
        }

        return $this;
    }

    public function removeImageUploaded(ImageUploaded $imageUploaded): self
    {
        if ($this->image_uploaded->contains($imageUploaded)) {
            $this->image_uploaded->removeElement($imageUploaded);
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
