<?php

namespace App\Entity;

use App\Repository\UserRepository;
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
}
