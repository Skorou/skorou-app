<?php

namespace App\Entity;

use App\Repository\TemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_index;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\ManyToOne(targetEntity=CreationType::class, inversedBy="templates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creation_type;

    /**
     * @ORM\OneToMany(targetEntity=TemplateFormField::class, mappedBy="template")
     */
    private $template_form_field;

    public function __construct()
    {
        $this->creation_category = new ArrayCollection();
        $this->template_form_field = new ArrayCollection();
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

    public function getOrderIndex(): ?int
    {
        return $this->order_index;
    }

    public function setOrderIndex(int $order_index): self
    {
        $this->order_index = $order_index;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getCreationType(): ?CreationType
    {
        return $this->creation_type;
    }

    public function setCreationType(?CreationType $creation_type): self
    {
        $this->creation_type = $creation_type;

        return $this;
    }

    /**
     * @return Collection|TemplateFormField[]
     */
    public function getTemplateFormField(): Collection
    {
        return $this->template_form_field;
    }

    public function addTemplateFormField(TemplateFormField $templateFormField): self
    {
        if (!$this->template_form_field->contains($templateFormField)) {
            $this->template_form_field[] = $templateFormField;
            $templateFormField->setTemplate($this);
        }

        return $this;
    }

    public function removeTemplateFormField(TemplateFormField $templateFormField): self
    {
        if ($this->template_form_field->contains($templateFormField)) {
            $this->template_form_field->removeElement($templateFormField);
            // set the owning side to null (unless already changed)
            if ($templateFormField->getTemplate() === $this) {
                $templateFormField->setTemplate(null);
            }
        }

        return $this;
    }
}
