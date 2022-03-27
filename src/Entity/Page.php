<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('get')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('get')]
    private ?string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups('get')]
    private ?string $description;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups('get')]
    private ?\DateTimeInterface $publishedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pages')]
    private ?User $author;

    #[ORM\OneToMany(mappedBy: 'Page', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[Pure] public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public static function create(string $title, string $description, User $author): self
    {
        $page = new self();
        $page->title = $title;
        $page->description = $description;
        $page->author = $author;
        $page->publishedAt = new \DateTimeImmutable("now");

        return $page;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPage($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPage() === $this) {
                $comment->setPage(null);
            }
        }

        return $this;
    }
}
