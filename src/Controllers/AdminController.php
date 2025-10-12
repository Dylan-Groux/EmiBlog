<?php

namespace App\Controllers;

use App\Controllers\AuthentificationController;
use App\Library\AbstractController;
use App\Models\Repositories\ArticleRepository;
use App\Models\Entities\Article;
use App\Services\Utils;
use App\Library\Route;
use App\Models\Exceptions\ValidationException;
use App\Views\View;
use App\Services\ValidationService;
use App\Services\ArticleService;
use App\Services\ViewTracker;
use App\Models\Repositories\TrackRepository;
use App\Models\Repositories\CommentRepository;

/**
 * Contrôleur de la partie admin.
 */

class AdminController extends AbstractController
{
    private AuthentificationController $authController;

    public function __construct()
    {
        $this->authController = new AuthentificationController();
    }

    /**
     * Affiche la page d'administration.
     * @return void
     */
    #[Route(path: ADMIN_REDIRECT, method: "GET")]
    public function showAdmin() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->authController->checkIfUserIsConnected();

        // On récupère les articles.
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        echo $view->render("admin", [
            'articles' => $articles
        ]);
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    #[Route(path: ADMIN_ARTICLE_FORM_ROUTE, method: "GET")]
    public function showUpdateArticleForm() : void
    {
        $this->authController->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe. 
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleRepository = new ArticleRepository();
        $article = $articleRepository->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide.
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        echo $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article.
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    #[Route(path: ADMIN_ARTICLE_SUBMIT_ROUTE, method: "POST")]
    public function updateArticle() : void
    {
        $this->authController->checkIfUserIsConnected();

        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // Crée l'objet Article avant la validation
        $article = new Article([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        $articleService = new ArticleService(new ArticleRepository());

        try {
            $articleService->addOrUpdateArticle($article);
            Utils::redirect(ADMIN_REDIRECT);
        } catch (ValidationException $e) {
            $view = new View("Edition d'un article");
            echo $view->render("updateArticleForm", [
                'article' => $article,
                'error' => $e->getMessage()
            ]);
        } catch (\PDOException $e) {
            $view = new View("Edition d'un article");
            echo $view->render("updateArticleForm", [
                'article' => $article,
                'error' => "Une erreur technique est survenue."
            ]);
        }
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    #[Route(path: ADMIN_ARTICLE_DELETE_ROUTE, method: "POST")]
    public function deleteArticle() : void
    {
        $this->authController->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleRepository = new ArticleRepository();
        $articleRepository->deleteArticle($id);

        // On redirige vers la page d'administration.
        Utils::redirect(ADMIN_REDIRECT);
    }

    /**
     * Affiche le tableau de bord.
     * @return void
     */
    #[Route(path: ADMIN_DASHBOARD_ROUTE, method: "GET")]
    public function showDashboard() : void
    {
        $this->authController->checkIfUserIsConnected();

        $articleRepository = new ArticleRepository();
        $trackRepository = new TrackRepository();
        $commentRepository = new CommentRepository();

        $articles = $articleRepository->getAllArticles();
        $dashboardArticles = [];

        foreach ($articles as $article) {
            $viewCount = $trackRepository->getViewCountByArticleId($article->getId());
            $commentCount = $commentRepository->getCountCommentsByArticleId($article->getId());
            $dashboardArticles[] = [
                'article' => $article,
                'viewCount' => $viewCount,
                'commentCount' => $commentCount
            ];
        }

        // On affiche la page du tableau de bord.
        $view = new View("Tableau de bord");
        echo $view->render("adminDashboard", [
            'dashboardArticles' => $dashboardArticles
        ]);
    }
}
