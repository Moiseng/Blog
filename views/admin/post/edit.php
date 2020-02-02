<?php

use App\Auth;
use App\BddConnection;
use App\HTML\Form;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;
use App\ObjectHelper;

Auth::check();

$pdo = BddConnection::getPDO();
$postTable = new PostTable($pdo);
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list(); // recupere les catégorie sous forme de liste
$post = $postTable->find($params["id"]);
$categoryTable->hydratePosts([$post]);
$success = false;
$errors = [];

if (!empty($_POST)) {
    $v = new PostValidator($_POST, $postTable, $categories, $post->getID());
    ObjectHelper::hydrate($post, $_POST, ["name", "content", "slug", "created_at"]);

    if ($v->validate()) { // s'il n'y a pas d'érreur de validation
        $pdo->beginTransaction();
        $postTable->updatePost($post);
        $postTable->attatchCategories($post->getID(), $_POST["categories_ids"]);
        $pdo->commit();
        $categoryTable->hydratePosts([$post]);
        $success = true;
    } else {
        $errors = $v->errors(); // sauvegarde les erreurs dans mon tableau d'erreurs
    }
}

$form = new Form($post, $errors);

?>

<?php if ($success) : ?>
    <div class="alert alert-success">
        L'article a bien été modifié
    </div>
<?php endif; ?>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        L'article n'a pas pus être modifié, merci de corriger vos erreurs
    </div>
<?php endif; ?>
<h1>Editer l'article <?= htmlentities($post->getName()) ?></h1>

<!-- Insertion du formulaire -->
<?php require "_form.php"; ?>
