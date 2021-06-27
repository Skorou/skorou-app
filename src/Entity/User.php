<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50, name="company_name")
     */
    private $username;

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

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $faxNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyStatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capital;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apeCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siren;

    /**
     * token for forgotten password
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

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
        $this->isActive = false;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
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

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->username,
            $this->password
            // see section on salt below
            // $this->salt
        ));

    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);

    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getFaxNumber(): ?string
    {
        return $this->faxNumber;
    }

    public function setFaxNumber(?string $faxNumber): self
    {
        $this->faxNumber = $faxNumber;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompanyStatus(): ?string
    {
        return $this->companyStatus;
    }

    public function setCompanyStatus(?string $companyStatus): self
    {
        $this->companyStatus = $companyStatus;

        return $this;
    }

    public function getCapital(): ?int
    {
        return $this->capital;
    }

    public function setCapital(?int $capital): self
    {
        $this->capital = $capital;

        return $this;
    }

    public function getApeCode(): ?string
    {
        return $this->apeCode;
    }

    public function setApeCode(?string $apeCode): self
    {
        $this->apeCode = $apeCode;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

}
