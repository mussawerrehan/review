<?php

namespace App\Entity;

use App\Repository\WhishlistItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WhishlistItemRepository::class)
 */
class WhishlistItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=wishlist::class)
     */
    private $wishlist;

    /**
     * @ORM\ManyToOne(targetEntity=item::class, inversedBy="whishlistItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWishlist(): ?wishlist
    {
        return $this->wishlist;
    }

    public function setWishlist(?wishlist $wishlist): self
    {
        $this->wishlist = $wishlist;

        return $this;
    }

    public function getItem(): ?item
    {
        return $this->item;
    }

    public function setItem(?item $item): self
    {
        $this->item = $item;

        return $this;
    }
}
