<?php

use App\Auth;
use App\BddConnection;
use App\HTML\Form;
use App\Model\Post;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;
use App\ObjectHelper;

Auth::check();

$success = false;
$errors = [];
$pdo = BddConnection::getPDO();
$post = new Post();
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();
$post->setCreatedAt(date("Y-m-d H:i:s"));

if (!empty($_POST)) { // si les champs sont remplies
    $postTable = new PostTable($pdo);
    $v = new PostValidator($_POST, $postTable, $categories);
    ObjectHelper::hydrate($post, $_POST, ["name", "content", "slug", "created_at"]);

    if ($v->validate()) { // s'il n'y a pas d'érreur de validation
        $pdo->beginTransaction();
        $postTable->createPost($post);
        $postTable->attatchCategories($post->getID(), $_POST["categories_ids"]);
        $pdo->commit();
        header("Location: ". $router->url("post", ["slug" => $post->getSlug(), "id" => $post->getID()]). "?created=1");
        exit();
    } else {
        $errors = $v->errors(); // sauvegarde les erreurs dans mon tableau d'erreurs
    }
}

$form = new Form($post, $errors);

?>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        L'article n'a pas pus être enregistré, merci de corriger vos erreurs
    </div>
<?php endif; ?>

<h1>Créer un article</h1>

<!-- Insertion du formulaire -->
<?php require "_form.php"; ?>
