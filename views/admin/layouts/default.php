<!doctype html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($title) ? htmlentities($title) : "Mon site" ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="d-flex flex-column h-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="" class="navbar-brand">Mon site</a>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="<?= $router->url("admin_posts") ?>">Articles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= $router->url("admin_categories") ?>">Catégories</a>
        </li>
        <li class="nav-item">
            <form action="<?= $router->url("logout") ?>" method="post" style="display: inline">
                <button type="submit" class="nav-link" style="background: transparent; border: none;">Déconnexion</button>
            </form>
        </li>
    </ul>
</nav>

<div class="container mt-4">
    <!-- charge le contenu, $content, définie dans la méthode run de la class Router -->
    <?= $content ?>
</div>

<footer class="bg-light py-4 footer mt-auto">
    <div class="container">
        <?php if (defined("DEBUG_TIME")): ?>
            page générer en <?= round(1000 * (microtime(true) - DEBUG_TIME)) // retourne un nombre en milliseconde ?> ms
        <?php endif; ?>
    </div>
</footer>

</body>
</html>