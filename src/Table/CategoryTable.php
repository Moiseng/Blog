<?php

namespace App\Table;


use App\Model\Category;
use App\Model\Post;
use App\PaginatedQuery;
use PDO;

/**
 * Class CategoryTable
 * @package App\Table
 *
 * final ( une class qui ne peut être héritée )
 */
final class CategoryTable extends Table
{

    protected $table = "category";

    protected $class = Category::class;

    /**
     * Permet de rentrer dans les articles, les catégories associer
     *
     * @param Post[] $posts
     */
    public function hydratePosts(array $posts): void
    {
        /* Tableau contenant les articles, indexé par ID */
        $postByID = [];
        foreach ($posts as $post) {
            $post->setCategories([]); // réinitialisera les catégorie des articles
            $postByID[$post->getID()] = $post;
        }
        /* requete pour récupérer les catégories */
        $categories = $this->pdo
            ->query(
                "
                SELECT c.*, pc.post_id 
                FROM post_category pc 
                JOIN category c ON c.id = pc.category_id 
                WHERE pc.post_id IN (". implode(", ", array_keys($postByID)).")")
            ->fetchAll(PDO::FETCH_CLASS, $this->class);

        // parcourir les catégories
        foreach ($categories as $category) {
            $postByID[$category->getPostID()]->addCategory($category);
        }
    }

    /**
     * Permet de récupérer les résultats Paginer
     *
     */
    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table} ORDER BY id DESC",
            "SELECT COUNT(id) FROM {$this->table}", 5 // récupère le nombre d'article total
            ,$this->pdo
        );

        // récupère les articles
        $posts = $paginatedQuery->getItems($this->class);
        return [$posts, $paginatedQuery];
    }

    /**
     * Permet de récupérer les catégories sous forme de liste
     *
     * @return array
     */
    public function list(): array
    {
        $categories = $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
        $results = [];
        foreach ($categories as $category) {
            $results[$category->getID()] = $category->getName();
        }
        return $results;
    }

}
