<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="data.user")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image()
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Mem::class, mappedBy="creator", orphanRemoval=true)
     */
    private $mems;

    /**
     * @ORM\OneToMany(targetEntity=MemeTemplate::class, mappedBy="uploader", orphanRemoval=true)
     */
    private $templates;

    /**
     * @return Collection|MemeTemplate[]
     */
    public function getTemplates(): Collection
    {
        return $this->templates;
    }

    public function addTemplate(MemeTemplate $template): self
    {
        if (!$this->templates->contains($template)) {
            $this->templates[] = $template;
            $template->setUploader($this);
        }

        return $this;
    }

    public function removeTemplate(MemeTemplate $template): self
    {
        if ($this->mems->removeElement($template)) {
            // set the owning side to null (unless already changed)
            if ($template->getUploader() === $this) {
                $template->setUploader(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __construct()
    {
        $this->userMem = new ArrayCollection();
        $this->mems = new ArrayCollection();
        $this->updatedAt = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Mem[]
     */
    public function getMems(): Collection
    {
        return $this->mems;
    }

    public function addMem(Mem $mem): self
    {
        if (!$this->mems->contains($mem)) {
            $this->mems[] = $mem;
            $mem->setCreator($this);
        }

        return $this;
    }

    public function removeMem(Mem $mem): self
    {
        if ($this->mems->removeElement($mem)) {
            // set the owning side to null (unless already changed)
            if ($mem->getCreator() === $this) {
                $mem->setCreator(null);
            }
        }

        return $this;
    }
}
