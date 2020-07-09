<?php

namespace App\Entity;

use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FolderRepository::class)
 */
class Folder
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Creation::class, mappedBy="folder")
     */
    private $creations;

    /**
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Folder::class, mappedBy="parent")
     */
    private $children;

    public function __construct()
    {
        $this->creations = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $creation->setFolder($this);
        }

        return $this;
    }

    public function removeCreation(Creation $creation): self
    {
        if ($this->creations->contains($creation)) {
            $this->creations->removeElement($creation);
            // set the owning side to null (unless already changed)
            if ($creation->getFolder() === $this) {
                $creation->setFolder(null);
            }
        }

        return $this;
    }

    public function getParent(): ?Folder
    {
        return $this->parent;
    }

    public function setParent(Folder $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|Folder[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Folder $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Folder $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
}
