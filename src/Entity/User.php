<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"email"},
 * message="Un utilisateur s'est déjà inscrit avec cet email"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message="Vous entrez une URL valide pour votre avatar !")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Vous n'avez pas correctement confirmé votre mot de passe !")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Votre introduction doit au moin avoir 10 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="Votre description doit au moin avoir 100 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=FocusPays::class, mappedBy="author")
     */
    private $focusPays;

    /**
     * @ORM\OneToMany(targetEntity=FocusVille::class, mappedBy="author")
     */
    private $focusVilles;

    /**
     * @ORM\OneToMany(targetEntity=FocusLieu::class, mappedBy="author")
     */
    private $focusLieus;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";
    }

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
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName);
        }
    }

    public function __construct()
    {
        $this->focusPays = new ArrayCollection();
        $this->focusVilles = new ArrayCollection();
        $this->focusLieus = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|FocusPays[]
     */
    public function getFocusPays(): Collection
    {
        return $this->focusPays;
    }

    public function addFocusPay(FocusPays $focusPay): self
    {
        if (!$this->focusPays->contains($focusPay)) {
            $this->focusPays[] = $focusPay;
            $focusPay->setAuthor($this);
        }

        return $this;
    }

    public function removeFocusPay(FocusPays $focusPay): self
    {
        if ($this->focusPays->contains($focusPay)) {
            $this->focusPays->removeElement($focusPay);
            // set the owning side to null (unless already changed)
            if ($focusPay->getAuthor() === $this) {
                $focusPay->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FocusVille[]
     */
    public function getFocusVilles(): Collection
    {
        return $this->focusVilles;
    }

    public function addFocusVille(FocusVille $focusVille): self
    {
        if (!$this->focusVilles->contains($focusVille)) {
            $this->focusVilles[] = $focusVille;
            $focusVille->setAuthor($this);
        }

        return $this;
    }

    public function removeFocusVille(FocusVille $focusVille): self
    {
        if ($this->focusVilles->contains($focusVille)) {
            $this->focusVilles->removeElement($focusVille);
            // set the owning side to null (unless already changed)
            if ($focusVille->getAuthor() === $this) {
                $focusVille->setAuthor(null);
            }
        }

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
            $focusLieu->setAuthor($this);
        }

        return $this;
    }

    public function removeFocusLieu(FocusLieu $focusLieu): self
    {
        if ($this->focusLieus->contains($focusLieu)) {
            $this->focusLieus->removeElement($focusLieu);
            // set the owning side to null (unless already changed)
            if ($focusLieu->getAuthor() === $this) {
                $focusLieu->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles() {

        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword() {
       return $this->hash;
    }

    public function getSalt() {}

    public function getUsername() {
        return $this->email;
    }

    public function eraseCredentials() {}

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }
}
