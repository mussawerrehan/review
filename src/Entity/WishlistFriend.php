<?php

namespace App\Entity;

use App\Repository\WishlistFriendRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WishlistFriendRepository::class)
 */
class WishlistFriend
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Wishlist::class, inversedBy="wishlistFriends")
     */
    private $wishlist;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wishlistFriends")
     * @ORM\JoinColumn(nullable=false)
     */
    private $friend;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWishlist(): ?Wishlist
    {
        return $this->wishlist;
    }

    public function setWishlist(?Wishlist $wishlist): self
    {
        $this->wishlist = $wishlist;

        return $this;
    }

    public function getFriend(): ?User
    {
        return $this->friend;
    }

    public function setFriend(?User $friend): self
    {
        $this->friend = $friend;

        return $this;
    }
}
