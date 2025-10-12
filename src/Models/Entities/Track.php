<?php

namespace App\Models\Entities;

class Track {
    private int $articleId;
    private int $viewCount;
    
    public function __construct(int $articleId, int $viewCount) {
        $this->articleId = $articleId;
        $this->viewCount = $viewCount;
    }

    public function getArticleId(): int {
        return $this->articleId;
    }

    public function getViewCount(): int {
        return $this->viewCount;
    }
}
