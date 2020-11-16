<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=FocusPays::class, inversedBy="comments")
     */
    private $focusPays;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=FocusVille::class, inversedBy="comments")
     */
    private $focusVille;

    /**
     * @ORM\ManyToOne(targetEntity=FocusLieu::class, inversedBy="comments")
     */
    private $focusLieu;

    /**
     * Permet de mettre en place la date de création
     * 
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist() {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFocusPays(): ?FocusPays
    {
        return $this->focusPays;
    }

    public function setFocusPays(?FocusPays $focusPays): self
    {
        $this->focusPays = $focusPays;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getFocusVille(): ?FocusVille
    {
        return $this->focusVille;
    }

    public function setFocusVille(?FocusVille $focusVille): self
    {
        $this->focusVille = $focusVille;

        return $this;
    }

    public function getFocusLieu(): ?FocusLieu
    {
        return $this->focusLieu;
    }

    public function setFocusLieu(?FocusLieu $focusLieu): self
    {
        $this->focusLieu = $focusLieu;

        return $this;
    }
}
