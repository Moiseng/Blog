<?php

use App\Auth;
use App\BddConnection;
use App\Model\Category;
use App\Table\CategoryTable;

Auth::check();

$title = "Gestion des catégories";
$categorie = new Category();
$pdo = BddConnection::getPDO();
[$categories, $pagination] = (new CategoryTable($pdo))->findPaginated();



$link = $router->url("admin_categories");

?>

<?php if (isset($_GET["created"])) : ?>
    <div class="alert alert-success">
        La catégorie a bien été créé
    </div>
<?php endif; ?>

<?php if (isset($_GET["delete"])) : ?>
    <div class="alert alert-success">
        L'article a bien été supprimé
    </div>
<?php endif; ?>

<a class="btn btn-primary mb-3" href="<?= $router->url("admin_category_new") ?>">Ajouter une catégorie</a>
<table class="table">
    <thead class="thead-light">
    <tr>
        <th>#</th>
        <th>Titre</th>
        <th>Slug</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $category) : ?>
        <tr>
            <td>#<?= $category->getID() ?></td>
            <td>
                <a href="<?= $router->url("admin_category", ["id" => $category->getID()]) ?>">
                    <?= htmlentities($category->getName()) ?>
                </a>
            </td>
            <td>
                <a href="<?= $router->url("category", ["slug" => $category->getSlug(),"id" => $category->getID()]) ?>">
                    <?= htmlentities($category->getSlug()) ?>
                </a>
            </td>
            <td>
                <a class="btn btn-primary" href="<?= $router->url("admin_category", ["id" => $category->getID()]) ?>">
                    Editer
                </a>
                <form action="<?= $router->url("admin_category_delete", ["id" => $category->getID()]) ?>"
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
