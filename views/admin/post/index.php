<?php

use App\Auth;
use App\BddConnection;
use App\Table\PostTable;

Auth::check();

$title = "Administration";
$pdo = BddConnection::getPDO();

try {
    [$posts, $pagination] = (new PostTable($pdo))->findPaginated();
} catch (Exception $e) {
    $e->getMessage();
}

$link = $router->url("admin_posts");

?>

<?php if (isset($_GET["delete"])) : ?>
<div class="alert alert-success">
    L'article a bien été supprimé
</div>
<?php endif; ?>

<a class="btn btn-primary mb-3 ml-auto" href="<?= $router->url("admin_post_new")?>">Ajouter un article</a>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post) : ?>
            <tr>
                <td>#<?= $post->getID() ?></td>
                <td>
                    <a href="<?= $router->url("admin_post", ["id" => $post->getID()]) ?>">
                        <?= htmlentities($post->getName()) ?>
                    </a>
                </td>
                <td>
                    <a class="btn btn-primary" href="<?= $router->url("admin_post", ["id" => $post->getID()]) ?>">
                        Editer
                    </a>
                    <form action="<?= $router->url("admin_post_delete", ["id" => $post->getID()]) ?>"
                        onsubmit="return confirm('Voulez vous vraiment effectuer cette action?')" method="post" style="display: inline">
                        <button class="btn btn-danger" type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="d-flex justify-content-between my-4">
    <!-- Button précédente -->
    <?= $pagination->previousLink($link) ?>

    <!-- Button suivant -->
    <?= $pagination->nextLink($link) ?>
</div>
