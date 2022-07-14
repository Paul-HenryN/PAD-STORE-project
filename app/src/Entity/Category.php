<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Article::class)]
    private $articles;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Budget::class)]
    private $budgets;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: LeftBudget::class, orphanRemoval: true)]
    private $leftBudgets;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->budgets = new ArrayCollection();
        $this->leftBudgets = new ArrayCollection();
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

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, budget>
     */
    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function addBudget(budget $budget): self
    {
        if (!$this->budgets->contains($budget)) {
            $this->budgets[] = $budget;
            $budget->setCategory($this);
        }

        return $this;
    }

    public function removeBudget(budget $budget): self
    {
        if ($this->budgets->removeElement($budget)) {
            // set the owning side to null (unless already changed)
            if ($budget->getCategory() === $this) {
                $budget->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }

    /**
     * @return Collection<int, LeftBudget>
     */
    public function getLeftBudgets(): Collection
    {
        return $this->leftBudgets;
    }

    public function addLeftBudget(LeftBudget $leftBudget): self
    {
        if (!$this->leftBudgets->contains($leftBudget)) {
            $this->leftBudgets[] = $leftBudget;
            $leftBudget->setCategory($this);
        }

        return $this;
    }

    public function removeLeftBudget(LeftBudget $leftBudget): self
    {
        if ($this->leftBudgets->removeElement($leftBudget)) {
            // set the owning side to null (unless already changed)
            if ($leftBudget->getCategory() === $this) {
                $leftBudget->setCategory(null);
            }
        }

        return $this;
    }
}
