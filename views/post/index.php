<?php

use App\BddConnection;
use App\Table\PostTable;

/* nombre d'éléments par page */
$perPage = 12;
$title = "Mon blog";

/* Initialisation de PDO */
$pdo = BddConnection::getPDO();

$table = new PostTable($pdo);
[$posts, $pagination] = $table->findPaginated();

$link = $router->url("home");
?>
<h1>Mon blog</h1>

<div class="row">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-3">
            <?php require "card.php" ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="d-flex justify-content-between my-4">
    <!-- Button précédente -->
    <?= $pagination->previousLink($link) ?>

    <!-- Button suivant -->
    <?= $pagination->nextLink($link) ?>
</div>