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
        $view->render("admin", [
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
        $view->render("updateArticleForm", [
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

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new ValidationException("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        // On ajoute l'article.
        $articleRepository = new ArticleRepository();
        $articleRepository->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect(ADMIN_REDIRECT);
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
}
