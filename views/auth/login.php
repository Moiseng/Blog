<?php


use App\BddConnection;
use App\HTML\Form;
use App\Model\User;
use App\Table\Exception\NotFoundException;
use App\Table\UserTable;

$user = new User();
$errors = [];

if (!empty($_POST)) { // si les champs sont remplis
    $user->setUsername($_POST["username"]); // re remplie le champ username
    $errors["password"] = "Identifiant ou mot de passe incorrect";
    if (!empty($_POST["username"]) && !empty($_POST["password"])) { // si les champs sont remplies
        $pdo = BddConnection::getPDO();
        $usernameTable = new UserTable($pdo);
        try {
            $u = $usernameTable->findByUsername($_POST["username"]);
            /* Vérifie si le mot de passer entrer, correspond au mot de passe dans la bdd */
            if (password_verify($_POST["password"], $u->getPassword()) === true) { // si le mdp correspond
                session_start(); // je démarre la session
                $_SESSION["auth"] = $u->getID(); // sauvegarde l'id de l'utilisateur en session
                header("Location: {$router->url("admin_posts")}");
                exit();
            }
        } catch (NotFoundException $e) {
        }
    }
}

$form = new Form($user, $errors);

?>

<h1>Se connecter</h1>

<?php if (isset($_GET["forbidden"])) : ?>
    <div class="alert alert-danger">
        Vous ne pouvez pas accéder à cette page
    </div>
<?php endif; ?>

<?php if (isset($_GET["logout"])) : ?>
    <div class="alert alert-success">
        Vous êtes bien déconnecter
    </div>
<?php endif; ?>

<form action="<?= $router->url("login") ?>" method="POST">
    <?= $form->input("username", "Nom d'utilisateur") ?>
    <?= $form->input("password", "Mot de passe", "password"); ?>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>