<?php

/**
 * filepath: src/config/routes.php
 * Définit les constantes de routes utilisées dans l'application.
 */

/**
 * Route pour le Contrôleur @var AdminController.php
 */
define('ADMIN_ARTICLE_FORM_ROUTE', '/admin/articleForm');
define('ADMIN_ARTICLE_DELETE_ROUTE', '/admin/delete');
define('ADMIN_ARTICLE_SUBMIT_ROUTE', '/admin/article');
define('ADMIN_REDIRECT', '/admin');

/**
 * Route pour le Contrôleur @var ArticleController.php
 */
define('ARTICLE_ROUTE', '/article');
define('LOGIN_ROUTE', '/login');
define('HOME_ROUTE', '/');
define('ARTICLE_APROPOS_ROUTE', '/apropos');

/**
 * Route pour le Contrôleur @var AuthentificationController.php
 */
define('ADMIN_CHECK_CONNECTED_ROUTE', '/admin/check');
define('ADMIN_CONNECTION_FORM_ROUTE', '/admin/connectionForm');
define('ADMIN_CONNECTION_ROUTE', '/admin/connection');
define('ADMIN_LOGOUT_ROUTE', '/admin/disconnect');

/**
 * Route pour le Contrôleur @var CommentController.php
 */
define('COMMENT_SUBMIT_ROUTE', '/comment/add');