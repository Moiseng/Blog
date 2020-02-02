<form action="" method="post">
    <?= $form->input("name", "Titre") ?>
    <?= $form->input("slug", "Le Slug") ?>
    <?= $form->select("categories_ids", "Catégories", $categories) ?>
    <?= $form->textarea("content", "Contenu") ?>
    <?= $form->input("created_at", "Date de création") ?>
    <button type="submit" class="btn btn-primary">
        <!-- si l'id est définie en base de donnée -->
        <?php if ($post->getID() !== null ) : ?>
            Modifier
        <!-- si l'id n'existe pas -->
        <?php else : ?>
            Créer
        <?php endif ?>
    </button>
</form>