<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('get')]
    private int $id;

    #[ORM\Column(type: 'text')]
    #[Groups('get')]
    private ?string $content;

    #[ORM\ManyToOne(targetEntity: Page::class, cascade:["persist"], inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Page $page;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    private ?Comment $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade:["persist"])]
    #[Groups('get')]
    private Collection $replies;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $publishedAt;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ["persist"], inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author;

    #[Pure] public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public static function create(string $content, User $author, Page $page): self
    {
        $comment = new self();
        $comment->content = $content;
        $comment->author = $author;
        $comment->page = $page;
        $comment->publishedAt = new \DateTimeImmutable("now");

        return $comment;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getParent() === $this) {
                $reply->setParent(null);
            }
        }

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
}
