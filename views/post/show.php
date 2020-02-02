<?php

use App\BddConnection;
use App\Table\CategoryTable;
use App\Table\PostTable;


$id = (int)$params["id"]; /* la variable params définie dans la méthode Run de l'objet Router */
$slug = $params["slug"];

/* Connexion a la BDD */
$pdo = BddConnection::getPDO();

/* Récupération de l'article par l'id */
$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]); // prend en paramêtre l'article courrant

/* Vérification de l'authenticité du slug */
if ($post->getSlug() !== $slug) {
    $url = $router->url("post", ["slug" => $post->getSlug(), "id" => $id]);
    http_response_code(301);
    header("Location: {$url}");
    exit();
}

?>

<?php if (isset($_GET["created"])) : ?>
    <div class="alert alert-success">
        L'article a bien été créé
    </div>
<?php endif; ?>

<h1 class="card-title"><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format("d F Y") ?></p>

<!-- listening des catégories -->
<?php foreach ($post->getCategories() as $k => $category): ?>
    <?php if ($k > 0) : ?>
    ,
    <?php endif;
    $url_category = $router->url("category", ["id" => $category->getID(), "slug" => $category->getSlug()]);
    ?>
    <a href="<?= $url_category ?>"><?= htmlentities($category->getName()) ?></a>
<?php endforeach; ?>
<p><?= $post->getFormatedContent() ?></p>