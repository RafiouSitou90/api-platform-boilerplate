<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'put',
        'delete',
        'get' => [
            'normalization_context' => [
                'groups' => [
                    'read:Posts:collection',
                    'read:Posts:item',
                    'read:Posts:Category',
                ]
            ]
        ]
    ],
    denormalizationContext: ['groups' => ['write:Posts:Post']],
    normalizationContext: ['groups' => ['read:Posts:collection']],
    paginationClientItemsPerPage: true,
    paginationItemsPerPage: 2,
    paginationMaximumItemsPerPage: 2
)]
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Posts:collection'])]
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:Posts:collection', 'write:Posts:Post']),
        Length(min: 5, groups: ['create:Posts:post'])
    ]
    private string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Posts:collection', 'write:Posts:Post'])]
    private string $slug;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['read:Posts:item', 'write:Posts:Post'])]
    private string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['read:Posts:item'])]
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts", cascade="persist")
     */
    #[
        Groups(['read:Posts:item', 'write:Posts:Post']),
        Valid
    ]
    private Category $category;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string[]
     */
    public static function validationGroups(self $post): array
    {
        return ['create:Posts:post'];
    }
}
