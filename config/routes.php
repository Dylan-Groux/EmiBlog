<?php

/**
 * filepath: src/config/routes.php
 * Définit les constantes de routes utilisées dans l'application.
 */

/**
 * Route pour le Contrôleur @var AdminController.php
 */
define('ADMIN_ARTICLE_FORM_ROUTE', '/admin/articleForm'); /** Formulaire d\'ajout d\'un article */
define('ADMIN_ARTICLE_DELETE_ROUTE', '/admin/delete'); /** Suppression d\'un article */
define('ADMIN_ARTICLE_SUBMIT_ROUTE', '/admin/article'); /** Soumission d\'un article */
define('ADMIN_REDIRECT', '/admin'); /** Redirection vers la page d\'admin */

/**
 * Route pour le Contrôleur @var ArticleController.php
 */
define('ARTICLE_ROUTE', '/article'); /** Détail d\'un article */
define('LOGIN_ROUTE', '/login'); /** Page de connexion */
define('HOME_ROUTE', '/'); /** Page d\'accueil */
define('ARTICLE_APROPOS_ROUTE', '/apropos'); /** Page à propos */

/**
 * Route pour le Contrôleur @var AuthentificationController.php
 */
define('ADMIN_CHECK_CONNECTED_ROUTE', '/admin/check'); /** Vérification de la connexion */
define('ADMIN_CONNECTION_FORM_ROUTE', '/admin/connectionForm'); /** Formulaire de connexion */
define('ADMIN_CONNECTION_ROUTE', '/admin/connection'); /** Connexion */
define('ADMIN_LOGOUT_ROUTE', '/admin/disconnect'); /** Déconnexion */

/**
 * Route pour le Contrôleur @var CommentController.php
 */
define('COMMENT_SUBMIT_ROUTE', '/comment/add'); /** Soumission d\'un commentaire */
