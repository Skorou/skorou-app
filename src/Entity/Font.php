<?php

namespace App\Entity;

use App\Repository\FontRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FontRepository::class)
 */
class Font
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=FontUser::class, mappedBy="font")
     */
    private $fontUsers;

    public function __construct()
    {
        $this->fontUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): self
    {
        $this->file_name = $file_name;

        return $this;
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
     * @return Collection|FontUser[]
     */
    public function getFontUsers(): Collection
    {
        return $this->fontUsers;
    }

    public function addFontUser(FontUser $fontUser): self
    {
        if (!$this->fontUsers->contains($fontUser)) {
            $this->fontUsers[] = $fontUser;
            $fontUser->setFont($this);
        }

        return $this;
    }

    public function removeFontUser(FontUser $fontUser): self
    {
        if ($this->fontUsers->contains($fontUser)) {
            $this->fontUsers->removeElement($fontUser);
            // set the owning side to null (unless already changed)
            if ($fontUser->getFont() === $this) {
                $fontUser->setFont(null);
            }
        }

        return $this;
    }
}
