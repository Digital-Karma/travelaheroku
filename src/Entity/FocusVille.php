<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FocusVilleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FocusVilleRepository::class)
 * ORM\HasLifecycleCallbacks
 */
class FocusVille
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255, minMessage="Le titre doit faire plus de 10 caractères !", maxMessage="Le titre ne peut pas faire plus de 255 caractères !")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, minMessage="L'introduction doit faire plus de 20 caractères !")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageCover;

    /**
     * @ORM\ManyToOne(targetEntity=FocusPays::class, inversedBy="focusVilles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $focusPays;

    /**
     * @ORM\OneToMany(targetEntity=FocusLieu::class, mappedBy="focusVille", orphanRemoval=true)
     */
    private $focusLieus;

    /**
     * @ORM\OneToOne(targetEntity=MarkerVille::class, mappedBy="focusVille", cascade={"persist", "remove"})
     */
    private $markerVille;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="focusVilles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="focusVille", orphanRemoval=true)
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
        $this->focusLieus = new ArrayCollection();
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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

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

    public function getImageCover(): ?string
    {
        return $this->imageCover;
    }

    public function setImageCover(string $imageCover): self
    {
        $this->imageCover = $imageCover;

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

    /**
     * @return Collection|FocusLieu[]
     */
    public function getFocusLieus(): Collection
    {
        return $this->focusLieus;
    }

    public function addFocusLieu(FocusLieu $focusLieu): self
    {
        if (!$this->focusLieus->contains($focusLieu)) {
            $this->focusLieus[] = $focusLieu;
            $focusLieu->setFocusVille($this);
        }

        return $this;
    }

    public function removeFocusLieu(FocusLieu $focusLieu): self
    {
        if ($this->focusLieus->contains($focusLieu)) {
            $this->focusLieus->removeElement($focusLieu);
            // set the owning side to null (unless already changed)
            if ($focusLieu->getFocusVille() === $this) {
                $focusLieu->setFocusVille(null);
            }
        }

        return $this;
    }

    public function getMarkerVille(): ?MarkerVille
    {
        return $this->markerVille;
    }

    public function setMarkerVille(MarkerVille $markerVille): self
    {
        $this->markerVille = $markerVille;

        // set the owning side of the relation if necessary
        if ($markerVille->getFocusVille() !== $this) {
            $markerVille->setFocusVille($this);
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
            $comment->setFocusVille($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFocusVille() === $this) {
                $comment->setFocusVille(null);
            }
        }

        return $this;
    }
}
