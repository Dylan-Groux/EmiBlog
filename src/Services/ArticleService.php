<?php
namespace App\Services;

use App\Models\Entities\Article;
use App\Models\Repositories\ArticleRepository;
use App\Services\ValidationService;
use App\Models\Exceptions\ValidationException;
use PDOException;

class ArticleService
{
    private ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Ajoute un article après validation.
     * @throws ValidationException
     * @throws PDOException
     * @return int L'ID de l'article créé
     */
    public function addOrUpdateArticle(Article $article): int
    {
        ValidationService::checkNotEmptyFields([
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);

        try {
            return $this->repository->addOrUpdateArticle($article);
        } catch (PDOException $e) {
            // Ici tu peux logger l’erreur ou la propager
            throw $e;
        }
    }
}
