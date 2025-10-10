<?php
    /**
     * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun.
     * Et un formulaire pour ajouter un article.
     */

    use App\Services\Utils;
?>

<h2>Edition des articles</h2>

<div class="adminArticle">
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="content"><?= $article->getContent(200) ?></div>
            <div><a class="submit" href="index.php?route=/admin/articleForm&id=<?= $article->getId() ?>">Modifier</a></div>
            <div>
                <form action="index.php?route=/admin/deleteArticle&id=<?= $article->getId() ?>" method="POST" style="display:inline;">
                    <button class="submit" type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer</button>
                </form>
            </div>
        </div>
    <?php } ?>
</div>

<a class="submit" href="index.php?route=/admin/articleForm">Ajouter un article</a>
