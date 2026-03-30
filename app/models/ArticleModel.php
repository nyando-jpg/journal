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
    public function getAll(?string $search = null): array
    {
        $sql = 'SELECT ji.*, ju.nom AS admin_nom FROM journal_info ji LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user';

        if ($search !== null && $search !== '') {
            $sql .= ' WHERE ji.titre LIKE :search OR ji.details LIKE :search OR ju.nom LIKE :search OR ji.date LIKE :search OR CAST(ji.id AS CHAR) LIKE :search';
        }

        $sql .= ' ORDER BY ji.date DESC';
        $stmt = $this->db->prepare($sql);

        if ($search !== null && $search !== '') {
            $like = '%' . $search . '%';
            $stmt->bindValue(':search', $like);
        }

        $stmt->execute();
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
