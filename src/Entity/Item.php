<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    const MAX_NAME_LENGTH = 255;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=WhishlistItem::class, mappedBy="item")
     */
    private $whishlistItems;

    public function __construct()
    {
        $this->whishlistItems = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|WhishlistItem[]
     */
    public function getWhishlistItems(): Collection
    {
        return $this->whishlistItems;
    }

    public function addWhishlistItem(WhishlistItem $whishlistItem): self
    {
        if (!$this->whishlistItems->contains($whishlistItem)) {
            $this->whishlistItems[] = $whishlistItem;
            $whishlistItem->setItem($this);
        }

        return $this;
    }

    public function removeWhishlistItem(WhishlistItem $whishlistItem): self
    {
        if ($this->whishlistItems->removeElement($whishlistItem)) {
            // set the owning side to null (unless already changed)
            if ($whishlistItem->getItem() === $this) {
                $whishlistItem->setItem(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
