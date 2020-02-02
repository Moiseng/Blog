<?php

use App\BddConnection;
use App\Table\CategoryTable;

$id = (int)$params["id"]; // la variable params définie dans la méthode " run ", de l'objet Router
$slug = $params["slug"];

/* Connexion a la BDD */
$pdo = BddConnection::getPDO();

$category = (new CategoryTable($pdo))->find($id);

/* Vérification de l'authenticité du slug */
if ($category->getSlug() !== $slug) {
    $url = $router->url("category", ["slug" => $category->getSlug(), "id" => $id]);
    http_response_code(301);
    header("Location: {$url}");
    exit();
}

/* variable qui définie le titre de la page */
$title = "Catégorie {$category->getName()}";

/* Initialisation de PDO */
$pdo = BddConnection::getPDO();

[$posts, $paginatedQuery] = (new \App\Table\PostTable($pdo))->findPaginatedCategory($category->getID());

$link =  $router->url("category", ["id" => $category->getID(), "slug" => $category->getSlug()]);

?>
<h1>Catégorie <?= htmlentities($category->getName()) ?></h1>

<div class="row">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__). DIRECTORY_SEPARATOR . "post/card.php" ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="d-flex justify-content-between my-4">
    <!-- Button précédente -->
    <?= $paginatedQuery->previousLink($link) ?>

    <!-- Button suivant -->
    <?= $paginatedQuery->nextLink($link)?>
</div>