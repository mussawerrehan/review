<?php

namespace App\Entity;

use App\Repository\FriendRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FriendRepository::class)
 */
class Friend
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friends")
     * @ORM\JoinColumn(nullable=false)
     */
    private $first_user_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $second_user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstUserId(): ?User
    {
        return $this->first_user_id;
    }

    public function setFirstUserId(?User $first_user_id): self
    {
        $this->first_user_id = $first_user_id;

        return $this;
    }

    public function getSecondUserId(): ?User
    {
        return $this->second_user_id;
    }

    public function setSecondUserId(?User $second_user_id): self
    {
        $this->second_user_id = $second_user_id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

}
