<?php

namespace app\models;

class CategoryModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT id_categorie, nom_categorie FROM journal_categories ORDER BY nom_categorie ASC');
        return $stmt->fetchAll();
    }

    public function exists(int $idCategorie): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM journal_categories WHERE id_categorie = ? LIMIT 1');
        $stmt->execute([$idCategorie]);

        return (bool) $stmt->fetchColumn();
    }
}
