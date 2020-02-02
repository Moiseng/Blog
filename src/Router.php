<?php

namespace App;

use AltoRouter;
use App\Security\ForbiddenException;

class Router
{

    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var AltoRouter
     */
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    /**
     * Méthode permettant d'enregistre une URL
     * @param string $url
     * @param string $view
     * @param string|null $viewName
     * @return Router
     * @throws \Exception
     */
    public function get(string $url, string $view, ?string $viewName = null): self
    {
        $this->router->map("GET", $url, $view, $viewName);
        return $this;
    }

    /**
     * Méthode permettant d'enregistre une URL
     * @param string $url
     * @param string $view
     * @param string|null $viewName
     * @return Router
     * @throws \Exception
     */
    public function post(string $url, string $view, ?string $viewName = null): self
    {
        $this->router->map("POST", $url, $view, $viewName);
        return $this;
    }

    /**
     * Méthode permettant de générer une route accessible en POST et en GET
     * @param string $url
     * @param string $view
     * @param string|null $viewName
     * @return Router
     * @throws \Exception
     */
    public function match(string $url, string $view, ?string $viewName = null): self
    {
        $this->router->map("POST|GET", $url, $view, $viewName);
        return $this;
    }

    /**
     * Génère un URL
     * @param string $name
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function url(string $name, array $params = [])
    {
        return $this->router->generate($name, $params);
    }

    public function run(): self
    {
        $match = $this->router->match(); // vérifie la route renvoyer existe
        $view = $match["target"]; // récupre le template
        $params = $match["params"]; // sauvegarde les paramètres de l'url
        $router = $this;
        $isAdmin = strpos($view, "admin/") !== false; // si l'url commence par "admin/"
        $layout = "";
        if ($isAdmin) {
            $layout = "admin/layouts/default";
        } else {
            $layout = "layouts/default";
        }
        if ($view === null) {
            $view = "e404";
        }
        try {
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . $view . ".php"; // charge le fichier a partir du chemin du fichier passé en parametre, et rajoute le .php a la fin
            $content = ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . $layout .".php";
        } catch (ForbiddenException $e) {
            header("Location: " . $router->url("login") . "?forbidden=1");
            exit();
        }
        return $this;
    }

}
