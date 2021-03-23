<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MemeTemplateRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=MemeTemplateRepository::class)
 * @ORM\Table(name="data.meme_template")
 * @Vich\Uploadable()
 */
class MemeTemplate
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $template;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="templates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $uploader;

    /**
     * @return mixed
     */
    public function getUploader(): ?User
    {
        return $this->uploader;
    }

    /**
     * @param mixed $uploader
     */
    public function setUploader(?User $uploader): User
    {
        return $this->uploader = $uploader;
    }

    public function __construct() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @Vich\UploadableField(mapping="templates", fileNameProperty="template")
     */
    private $templateFile;

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate(mixed $template): void
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getTemplateFile()
    {
        return $this->templateFile;
    }

    /**
     * @param mixed $templateFile
     */
    public function setTemplateFile($templateFile): void
    {
        $this->templateFile = $templateFile;
        if ($templateFile) {
            $this->updatedAt = new \DateTime();
        }
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
