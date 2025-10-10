<?php

namespace App\Controllers;

use App\Models\ArticleManager;
use App\Models\CommentManager;
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
    #[Route(path: "/", method: "GET")]
    public function showHome() : void
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        $view = new View("Accueil");
        $view->render("home", ['articles' => $articles]);
    }

    /**
     * Affiche le détail d'un article.
     * @return void
     */
    #[Route(path: "/article", method: "GET")]
    public function showArticle() : void
    {
        // Récupération de l'id de l'article demandé.
        $id = Utils::request("id", -1);

        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);
        
        if (!$article) {
            throw new NotFoundException("L'article demandé n'existe pas.");
        }

        $tracker = new ViewTracker();
        $tracker->trackView($id);

        $commentManager = new CommentManager();
        $comments = $commentManager->getAllCommentsByArticleId($id);

        $view = new View($article->getTitle());
        $view->render("detailArticle", ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Affiche le formulaire d'ajout d'un article.
     * @return void
     */
    #[Route(path: "/article/ajouter", method: "GET")]
    public function addArticle() : void
    {
        $view = new View("Ajouter un article");
        $view->render("addArticle");
    }

    /**
     * Affiche la page "à propos".
     * @return void
     */
    #[Route(path: "/apropos", method: "GET")]
    public function showApropos() {
        $view = new View("A propos");
        $view->render("apropos");
    }
}
