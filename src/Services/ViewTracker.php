<?php

namespace App\Services;

use App\Models\Infrastructure\DBManager;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ViewTracker {

    private DBManager $db;
    
    public function __construct() {
        $this->db = DBManager::getInstance();
    }

    public function articleTrackView(?int $articleId): void {
            // Vérifie si une ligne existe déjà pour cet article ET cette vue
            $stmt = $this->db->getPDO()->prepare('SELECT id, view_count FROM track WHERE article_id = ?');
            $stmt->execute([$articleId]);
            $track = $stmt->fetch();

            $stmt = $this->db->getPDO()->prepare('UPDATE track SET view_count = view_count + 1, created_at = NOW() WHERE id = :id');
            $stmt->execute(['id' => $track['id']]);
    }
}
