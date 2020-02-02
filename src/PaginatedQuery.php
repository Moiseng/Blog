<?php

namespace App;

use App\Model\Post;
use Exception;
use PDO;

class PaginatedQuery
{
    /* le nombre d'item à afficher par page */
    const ITEMS_PER_PAGE = 12;

    private $pdo;

    private $query;

    private $queryCount;

    private $itemsPerPage;

    private $items;

    /* retourne le nombre d'éléments total dans la bdd */
    private $count;

    public function __construct(string $query, string $queryCount, int $itemsPerPage = self::ITEMS_PER_PAGE,?\PDO $pdo = null)
    {
        $this->pdo = $pdo;
        if ($pdo === null) {
            $this->pdo = BddConnection::getPDO();
        }
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * Permet d'obtenir les articles pour le listing
     *
     * @return array
     * @throws Exception
     */
    public function getItems(?string $classMapping = null): array
    {
        if ($this->items === null) {
            //Récupère la page courrante
            $currentPage = $this->getCurrentPage();
            // recuperation de nombres d'article dans la bdd
            $pages = $this->getPages(); // recupere le nombre des pages
            if ($currentPage > $pages) {
                throw new Exception("Cette page n'existe pas");
            }
            if ($currentPage <= 0) {
                throw new Exception("Numero de page invalide");
            }
            $offset = $this->itemsPerPage * ($currentPage - 1); // recupere l'offset
            $this->items = $this->pdo
                ->query(
                    $this->query.
                    " LIMIT {$this->itemsPerPage} OFFSET {$offset}")
                ->fetchAll(PDO::FETCH_CLASS, $classMapping);
        }
        return $this->items;
    }

    public function previousLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage <= 1) {
            return null;
        }
        if ($currentPage > 2) {
            $link .= "?page=" . ($currentPage - 1);
        }
        return "<a href=\"{$link}\"class=\"btn btn-primary\">
            &laquo; Page précédente
                </a>";
    }

    public function nextLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage >= $pages) {
            return null;
        }
        $link .= "?page=" . ($currentPage + 1);
        $html = <<<HTML
                <a href="{$link}"class="btn btn btn-primary ml-auto">
                    Page suivante &raquo;
                </a>
HTML;
        return $html;
    }

    private function getCurrentPage(): int
    {
        /*
         * récupère l'Int du parametre "page" passé dans l'url,
         * définie la valeur 1 par défaut si celle ci n'existe pas
         */
        return URL::getPositiveInt("page", 1);
    }

    /**
     * Permet de récupérer le nombre des pages
     *
     * @return int
     */
    private function getPages(): int
    {
        if ($this->count === null) {
            $this->count = (int)$this->pdo
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];
        }
        return ceil($this->count / $this->itemsPerPage); // calcule le nombre de page
    }

}
