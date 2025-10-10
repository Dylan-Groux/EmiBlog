<?php

namespace App\Controllers;

use App\Models\Repositories\ArticleRepository;
use App\Models\Repositories\CommentRepository;
use App\Services\ViewTracker;
use App\Services\Utils;
use App\Views\View;
use App\Library\AbstractController;
use App\Library\Route;
use App\Models\Exceptions\NotFoundException;

class ArticleController extends AbstractController
{
    /**
     * Affiche la page d'accueil.
     * @return void
     */
    #[Route(path: HOME_ROUTE, method: "GET")]
    public function showHome() : void
    {
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();
        $view = new View("Accueil", MAIN_VIEW_PATH, TEMPLATE_VIEW_PATH);
        echo $view->render("home", ['articles' => $articles]);
    }

    /**
     * Affiche le détail d'un article.
     * @return void
     */
    #[Route(path: ARTICLE_ROUTE, method: "GET")]
    public function showArticle() : void
    {
        // Récupération de l'id de l'article demandé.
        $id = Utils::request("id", -1);

        $articleRepository = new ArticleRepository();
        $article = $articleRepository->getArticleById($id);

        if (!$article) {
            throw new NotFoundException("L'article demandé n'existe pas.");
        }

        $tracker = new ViewTracker();
        $tracker->trackView($id);

        $commentRepository = new CommentRepository();
        $comments = $commentRepository->getAllCommentsByArticleId($id);

        $view = new View($article->getTitle());
        echo $view->render("detailArticle", ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Affiche le formulaire d'ajout d'un article.
     * @return void
     */
    #[Route(path: "/article/ajouter", method: "GET")]
    public function addArticle() : void
    {
        $view = new View("Ajouter un article");
        echo $view->render("addArticle");
    }

    /**
     * Affiche la page "à propos".
     * @return void
     */
    #[Route(path: ARTICLE_APROPOS_ROUTE, method: "GET")]
    public function showApropos() {
        $view = new View("A propos");
        echo$view->render("apropos");
    }
}
