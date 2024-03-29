<?php

namespace App\Table;

use App\Model\Post;
use App\PaginatedQuery;

/**
 * Class PostTable
 * @package App\Table
 *
 * final ( une class qui ne peut être héritée )
 */
final class PostTable extends Table
{

    protected $table = "post";

    protected $class = Post::class;


    /**
     * Permet de récupérer les résultats Paginer
     *
     */
    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->table}", 12 // récupère le nombre d'article total
            ,$this->pdo
        );

        // récupère les articles
        $posts = $paginatedQuery->getItems($this->class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

    public function findPaginatedCategory(int $categoryID)
    {
        $paginatedQuery = new PaginatedQuery("
            SELECT p.*
            FROM {$this->table} p
            JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = {$categoryID}
            ORDER BY created_at DESC", "
            SELECT COUNT(category_id)
            FROM post_category
            WHERE category_id = {$categoryID}");

        $posts = $paginatedQuery->getItems(Post::class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

    /**
     * Méthode permettant de modifier les données de l'article
     *
     * @param Post $post
     * @throws \Exception
     */
    public function updatePost(Post $post): void
    {
        $this->update([
            "name" => $post->getName(),
            "slug" => $post->getSlug(),
            "content" => $post->getContent(),
            "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s")
        ], $post->getID());
        /*$this->pdo->exec("DELETE FROM post_category WHERE post_id = {$post->getID()}");
        $query = $this->pdo->prepare("INSERT INTO post_category SET post_id = ?, category_id = ?");
        foreach ($categoriesIds as $category) {
            $query->execute([
               $post->getID(),
               $category
            ]);
        }
        $this->pdo->commit();*/
    }

    /**
     * @param Post $post
     * @throws \Exception
     */
    public function createPost(Post $post): void
    {
        $id = $this->create([
           "name" => $post->getName(),
           "slug" => $post->getSlug(),
           "content" => $post->getContent(),
           "created_at" => $post->getCreatedAt()->format("Y-m-d H:i:s")
        ]);
        $post->setID($id);
    }

    public function attatchCategories(int $id, array $categoriesIds)
    {
        $this->pdo->exec("DELETE FROM post_category WHERE post_id = {$id}");
        $query = $this->pdo->prepare("INSERT INTO post_category SET post_id = ?, category_id = ?");
        foreach ($categoriesIds as $category) {
            $query->execute([
                $id,
                $category
            ]);
        }
    }

}