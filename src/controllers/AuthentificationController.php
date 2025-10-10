<?php

namespace App\Controllers;

use App\Models\UserManager;
use App\Services\Utils;
use App\Views\View;
use Exception;
use App\Models\ExceptionHandlerInterface;
use App\Models\Exceptions\NotFoundException;
use App\Models\ExceptionManager;
use App\Library\Route;
use App\Models\Exceptions\ValidationException;

class AuthentificationController
{
    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    #[Route(path: "/admin/check", method: "GET")]
    public function checkIfUserIsConnected() : void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    #[Route(path: "/admin/connectionForm", method: "GET")]
    public function displayConnectionForm() : void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    #[Route(path: "/admin/connection", method: "POST")]
    public function connectUser() : void
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new ValidationException("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
        /** @var ExceptionHandlerInterface $manager */
        $manager = new ExceptionManager();
        $manager->handleException(new NotFoundException("L'utilisateur demandé n'existe pas."));
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new ValidationException("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
        Utils::redirect("/admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    #[Route(path: "/admin/disconnect", method: "GET")]
    public function disconnectUser() : void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("/");
    }
}
