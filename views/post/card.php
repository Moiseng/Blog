<?php
/*$categories = [];
foreach ($post->getCategories() as $category) {
    $url_category = $router->url("category", ["id" => $category->getID(), "slug" => $category->getSlug()]);
    $categoryName = htmlentities($category->getName());
    $categories[] = <<<HTML
                <a href="{$url_category}">{$categoryName}</a>
HTML;
}*/

$categories = array_map(function ($category) use ($router) {
    $url_category = $router->url("category", ["id" => $category->getID(), "slug" => $category->getSlug()]);
    $categoryName = htmlentities($category->getName());
    return <<<HTML
                <a href="{$url_category}">{$categoryName}</a>
HTML;
}, $post->getCategories());


?>
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
        <p class="text-muted">
            <?= $post->getCreatedAt()->format("d F Y") ?>
            <?php if (!empty($post->getCategories())) : ?>
                :: <?= implode(", ", $categories) ?>
            <?php endif; ?>
        </p>
        <p><?= $post->getExcerpt() ?></p>
        <p>
            <a href="<?= $router->url("post", ["id" => $post->getID(), "slug" => $post->getSlug()]) ?>" class="btn btn-primary">Voir plus</a>
        </p>
    </div>
</div>