<?php

namespace App\Entity;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FocusLieuRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=FocusLieuRepository::class)
 * @UniqueEntity(
 *  fields={"title"},
 *  message="Un Focus Lieu possède déjà ce titre"
 * )
 */
class FocusLieu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255, minMessage="Le titre doit faire plus de 10 caractères !", maxMessage="Le titre ne peut pas faire plus de 255 caractères !")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=50, minMessage="Le contenu doit faire plus de 50 caractères !")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=FocusVille::class, inversedBy="focusLieus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $focusVille;

    /**
     * @ORM\OneToOne(targetEntity=MarkerLieu::class, mappedBy="focusLieu", cascade={"persist", "remove"})
     */
    private $markerLieu;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="focusLieus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="focusLieu", orphanRemoval=true)
     */
    private $comments;
    
      /**
     * Permet d'initialiser le slug
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initializeSlug() {
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    /**
     * Permet de récuperer le commentaire d'un auteur par rapport à une annonce
     *
     * @param User $author
     * @return Comment | null
     */
    public function getCommentFromAuthor(User $author){
        foreach ($this->comments as $comment) {
            if($comment->getAuthor() === $author) return $comment;
        }

        return null;
    }

    /**
     * Permet d'obtenir la moyenne globale des note pour cette annonce
     *
     * @return float
     */
    public function getAvgRatings() {
        //Calculer la somme des notations
        // on commence par appeler le tableau comments qui est un array collection, on le transform en tableau avec to array et il est reduit avec array_reduce
        $sum = array_reduce($this->comments->toArray(), function($total, $comment) {
            return $total + $comment->getRating();
        }, 0);

        //Faire la division pour avoir la moyenne
       if(count($this->comments) > 0) return $sum /count($this->comments);
       
       return 0;
    }

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getMarkerLieu(): ?MarkerLieu
    {
        return $this->markerLieu;
    }

    public function setMarkerLieu(MarkerLieu $markerLieu): self
    {
        $this->markerLieu = $markerLieu;

        // set the owning side of the relation if necessary
        if ($markerLieu->getFocusLieu() !== $this) {
            $markerLieu->setFocusLieu($this);
        }

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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFocusLieu($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFocusLieu() === $this) {
                $comment->setFocusLieu(null);
            }
        }

        return $this;
    }
}
