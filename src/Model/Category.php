<?php

namespace App\Model;

class Category
{
    private $id;

    private $name;

    private $slug;

    private $post_id;

    private $post;

    /**
     * Récupère l'id
     *
     * @return int|null
     */
    public function getID(): ?int
    {
        return $this->id;
    }

    public function setID(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Récupère le titre de la catégorie
     *
     * @return string|null
     */
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
     * Récupère le slug de la catégorie
     *
     * @return string|null
     */
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
     * Récupère l'id du post lié a la catégorie
     *
     * @return int|null
     */
    public function getPostID(): ?int
    {
        return $this->post_id;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

}
