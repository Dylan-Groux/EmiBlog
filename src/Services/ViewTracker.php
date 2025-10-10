<?php

namespace App\Services;

use App\Models\Infrastructure\DBManager;

class ViewTracker {

    private DBManager $db;
    
    public function __construct() {
        $this->db = DBManager::getInstance();
    }

    public function trackView($articleId) {
            // Vérifie si une ligne existe déjà pour cet article
            $stmt = $this->db->getPDO()->prepare('SELECT id, view_count FROM track WHERE article_id = ?');
            $stmt->execute([$articleId]);
            $track = $stmt->fetch();

            $id = $track['id'] ?? null;

            if ($track) {
                // Incrémente le compteur de vues
                $stmt = $this->db->getPDO()->prepare('UPDATE track SET view_count = view_count + 1, created_at = NOW() WHERE id = :id');
                $stmt->execute(['id' => $id]);
            } else {
                // Crée la ligne pour cet article
                $stmt = $this->db->getPDO()->prepare('INSERT INTO track (article_id, view_count, created_at) VALUES (:article_id, 1, NOW())');
                $stmt->execute(['article_id' => $articleId]);
            }
    }
}
