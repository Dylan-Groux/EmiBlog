<?php

namespace App\Views;

use App\Models\Exceptions\ViewException;
use Exception;

/**
 * Cette classe génère les vues en fonction de ce que chaque contrôlleur lui passe en paramètre.
 */
class View
{
    /**
     * Le titre de la page.
     */
    private string $title;
    private string $mainViewPath = MAIN_VIEW_PATH;
    private string $templateViewPath = TEMPLATE_VIEW_PATH;


    /**
     * Constructeur.
     */
    public function __construct($title, string $mainViewPath = MAIN_VIEW_PATH, string $templateViewPath = TEMPLATE_VIEW_PATH)
    {
        $this->title = $title;
        $this->mainViewPath = $mainViewPath;
        $this->templateViewPath = $templateViewPath;
    }
    
    /**
     * Cette méthode retourne une page complète.
     * @param string $viewPath : le chemin de la vue demandée par le controlleur.
     * @param array $params : les paramètres que le controlleur a envoyé à la vue.
     * @return string
     */
    public function render(string $viewName, array $params = []) : string
    {
        // On s'occupe de la vue envoyée
        $viewPath = $this->buildViewPath($viewName);
        $content = $this->renderViewFromTemplate($viewPath, $params);
        $title = $this->title;
        
        ob_start();
        require_once $this->mainViewPath;
        return ob_get_clean();
    }
    
    /**
     * Coeur de la classe, c'est ici qu'est généré ce que le controlleur a demandé.
     * @param $viewPath : le chemin de la vue demandée par le controlleur.
     * @param array $params : les paramètres que le controlleur a envoyés à la vue.
     * @throws Exception : si la vue n'existe pas.
     * @return string : le contenu de la vue.
     */
    private function renderViewFromTemplate(string $viewPath, array $params = []) : string
    {
        if (file_exists($viewPath)) {
            extract($params); // On transforme les diverses variables stockées dans le tableau "params" en véritables variables qui pourront être lues dans le template.
            ob_start();
            require_once $viewPath;
            return ob_get_clean();
        } else {
            throw new ViewException("La vue '$viewPath' est introuvable.");
        }
    }

    /**
     * Cette méthode construit le chemin vers la vue demandée.
     * @param string $viewName : le nom de la vue demandée.
     * @return string : le chemin vers la vue demandée.
     */
    private function buildViewPath(string $viewName) : string
    {
        return $this->templateViewPath . $viewName . '.php';
    }
}

