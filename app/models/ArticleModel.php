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
        $stmt = $this->db->query('SELECT ji.*, ju.nom AS admin_nom FROM journal_info ji LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user ORDER BY ji.date DESC');
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un article par son ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT ji.*, ju.nom AS admin_nom FROM journal_info ji LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user WHERE ji.id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Créer un nouvel article
     */
    public function create(int $idAdmin, string $titre, string $details): bool
    {
        $stmt = $this->db->prepare('INSERT INTO journal_info (date, id_admin, titre, details) VALUES (NOW(), ?, ?, ?)');
        return $stmt->execute([$idAdmin, $titre, $details]);
    }

    /**
     * Mettre à jour un article
     */
    public function update(int $id, int $idAdmin, string $titre, string $details): bool
    {
        $stmt = $this->db->prepare('UPDATE journal_info SET id_admin = ?, titre = ?, details = ? WHERE id = ?');
        return $stmt->execute([$idAdmin, $titre, $details, $id]);
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
