<?php

namespace App\Entity;

use App\Repository\CreationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CreationRepository::class)
 */
class Creation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $name = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="creations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="creations")
     */
    private $folder;

    /**
     * @ORM\ManyToMany(targetEntity=CreationType::class, inversedBy="creations")
     */
    private $creation_type;

    public function __construct()
    {
        $this->creation_type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?array
    {
        return $this->name;
    }

    public function setName(array $name): self
    {
        $this->name = $name;

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

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * @return Collection|CreationType[]
     */
    public function getCreationType(): Collection
    {
        return $this->creation_type;
    }

    public function addCreationType(CreationType $creationType): self
    {
        if (!$this->creation_type->contains($creationType)) {
            $this->creation_type[] = $creationType;
        }

        return $this;
    }

    public function removeCreationType(CreationType $creationType): self
    {
        if ($this->creation_type->contains($creationType)) {
            $this->creation_type->removeElement($creationType);
        }

        return $this;
    }
}
