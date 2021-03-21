<?php

namespace App\Entity;

use App\Repository\WishlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WishlistRepository::class)
 */
class Wishlist
{
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wishlists")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=WishlistFriend::class, mappedBy="wishlist")
     */
    private $wishlistFriends;

    public function __construct()
    {
        $this->wishlistFriends = new ArrayCollection();
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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection|WishlistFriend[]
     */
    public function getWishlistFriends(): Collection
    {
        return $this->wishlistFriends;
    }

    public function addWishlistFriend(WishlistFriend $wishlistFriend): self
    {
        if (!$this->wishlistFriends->contains($wishlistFriend)) {
            $this->wishlistFriends[] = $wishlistFriend;
            $wishlistFriend->setWishlist($this);
        }

        return $this;
    }

    public function removeWishlistFriend(WishlistFriend $wishlistFriend): self
    {
        if ($this->wishlistFriends->removeElement($wishlistFriend)) {
            // set the owning side to null (unless already changed)
            if ($wishlistFriend->getWishlist() === $this) {
                $wishlistFriend->setWishlist(null);
            }
        }

        return $this;
    }
}
