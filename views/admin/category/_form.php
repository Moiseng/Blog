<form action="" method="post">
    <?= $form->input("name", "Titre de la catégorie"); ?>
    <?= $form->input("slug", "Le Slug"); ?>
    <button class="btn btn-primary" type="submit">
        <?php if ($category->getID() !== null) : ?>
            Modifier
        <?php else : ?>
            Créer
        <?php endif; ?>
    </button>
</form>
