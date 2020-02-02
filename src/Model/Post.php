<?php

namespace App\Model;

use App\Helpers\Text;
use DateTime;

class Post
{

    const LIMIT_CONTENT = 100;

    private $id;

    private $name;

    private $slug;

    private $content;

    private $created_at;

    private $categories = [];

    /**
     * Récupère le titre de l'article
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Méthode qui permet de définir le nom ( titre ) de notre article
     *
     * @param string $name
     * @return Post
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Récupère un extrait du contenu de l'article
     *
     * @return string|null
     */
    public function getExcerpt(): ?string
    {
        if ($this->content === null) { // si le contenu n'est pas définie
            return null;
        }
        return nl2br(htmlentities(Text::excerpt($this->content, self::LIMIT_CONTENT)));
    }

    /**
     * Récupère la date de l'article
     *
     * @return DateTime
     * @throws \Exception
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    /**
     * Permet de définir la date de notre article
     *
     * @param string $date
     * @return Post
     */
    public function setCreatedAt(string $date): self
    {
        $this->created_at = $date;
        return $this;
    }

    /**
     * Récupère l'id
     *
     * @return int
     */
    public function getID(): ?int
    {
        return $this->id;
    }

    /**
     * Permet de définir l'id de l'article
     *
     * @param int $id
     * @return Post
     */
    public function setID(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Récupère le slug
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Permet de définir le slug de l'article
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Récupère le contenu
     *
     * @return string|null
     */
    public function getFormatedContent(): ?string
    {
        return nl2br(htmlentities($this->content));
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Méthode qui permet de définir le contenu de notre article
     *
     * @param string $content
     * @return Post
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getCategoriesIds(): array
    {
        $ids = [];
        foreach ($this->categories as $category)
        {
            $ids[] = $category->getID();
        }
        return $ids;
    }

    /**
     * @param array $categories
     * @return Post
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Permet d'ajouter des catégories a l'article
     *
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
        $category->setPost($this); // sauvegarde l'article associé a la category
    }

}
