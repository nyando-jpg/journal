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
    public function getAll(?string $search = null, ?int $categoryId = null): array
    {
        $sql = 'SELECT ji.*, ju.nom AS admin_nom, jc.nom_categorie FROM journal_info ji LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user LEFT JOIN journal_categories jc ON ji.id_categorie = jc.id_categorie';

        $where = [];

        if ($search !== null && $search !== '') {
            $where[] = '(ji.titre LIKE :search OR ji.details LIKE :search OR ju.nom LIKE :search OR jc.nom_categorie LIKE :search OR ji.date LIKE :search OR CAST(ji.id AS CHAR) LIKE :search)';
        }

        if ($categoryId !== null && $categoryId > 0) {
            $where[] = 'ji.id_categorie = :categoryId';
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY ji.date DESC';
        $stmt = $this->db->prepare($sql);

        if ($search !== null && $search !== '') {
            $like = '%' . $search . '%';
            $stmt->bindValue(':search', $like);
        }

        if ($categoryId !== null && $categoryId > 0) {
            $stmt->bindValue(':categoryId', $categoryId, \PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un article par son ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT ji.*, ju.nom AS admin_nom, jc.nom_categorie FROM journal_info ji LEFT JOIN journal_user ju ON ji.id_admin = ju.id_user LEFT JOIN journal_categories jc ON ji.id_categorie = jc.id_categorie WHERE ji.id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Créer un nouvel article
     */
    public function create(int $idAdmin, int $idCategorie, string $titre, string $details): bool
    {
        $stmt = $this->db->prepare('INSERT INTO journal_info (date, id_admin, id_categorie, titre, details) VALUES (NOW(), ?, ?, ?, ?)');
        return $stmt->execute([$idAdmin, $idCategorie, $titre, $details]);
    }

    /**
     * Mettre à jour un article
     */
    public function update(int $id, int $idAdmin, int $idCategorie, string $titre, string $details): bool
    {
        $stmt = $this->db->prepare('UPDATE journal_info SET id_admin = ?, id_categorie = ?, titre = ?, details = ? WHERE id = ?');
        return $stmt->execute([$idAdmin, $idCategorie, $titre, $details, $id]);
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
