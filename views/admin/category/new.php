<?php


use App\Auth;
use App\BddConnection;
use App\HTML\Form;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();

$category = new Category();
$errors = [];

if (!empty($_POST)) {
    $pdo = BddConnection::getPDO();
    $categoryTable = new CategoryTable($pdo);
    $v = new CategoryValidator($_POST, $categoryTable);
    ObjectHelper::hydrate($category, $_POST, ["name", "slug"]);

    if ($v->validate()) {
        $categoryTable->create([
                "name" => $category->getName(),
                "slug" => $category->getSlug()
        ]);
        header("Location: ". $router->url("admin_categories"). "?created=1");
        exit();
    } else {
        $errors = $v->errors();
    }
}

$form = new Form($category, $errors);
?>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        La catégorie n'a pas pu être enregistrée, merci de corriger vos erreurs
    </div>
<?php endif; ?>

<h1>Créer une catégorie</h1>

<!-- Introduction du formulaire -->
<?php require "_form.php"; ?>
