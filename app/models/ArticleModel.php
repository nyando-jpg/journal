<?php

namespace app\models;

class ArticleModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupérer tous les articles (du plus récent au plus ancien)
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM journal_info ORDER BY date DESC');
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un article par son ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM journal_info WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Créer un nouvel article
     */
    public function create(string $details): bool
    {
        $stmt = $this->db->prepare('INSERT INTO journal_info (date, details) VALUES (NOW(), ?)');
        return $stmt->execute([$details]);
    }

    /**
     * Mettre à jour un article
     */
    public function update(int $id, string $details): bool
    {
        $stmt = $this->db->prepare('UPDATE journal_info SET details = ? WHERE id = ?');
        return $stmt->execute([$details, $id]);
    }

    /**
     * Supprimer un article
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM journal_info WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
