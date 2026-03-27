<?php

namespace app\models;
use Flight;

class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db; 
    }

    // Insérer un nouvel utilisateur
    public function createUser($name, $mail, $password)
    {
        $query = $this->db->prepare("INSERT INTO journal_user (nom,  mdp,mail,is_admin) 
                                    VALUES (:nom,  :mdp,:mail, 0)"); // Ajout d'un utilisateur normal
        return $query->execute([
            'nom' => $name,
            'mail' => $mail,
            'mdp' => $password
        ]);   
    }

    // Vérifier si admin
    public function isAdmin($userId)
    {
        $query = $this->db->prepare("SELECT is_admin FROM journal_user WHERE id_user = :id_user");
        $query->execute(['id_user' => $userId]);
        $result = $query->fetch();

        return $result && $result['is_admin'] == 1;
    }

    // Transformer un utilisateur en admin
    public function promoteToAdmin($userId)
    {
        $query = $this->db->prepare("UPDATE journal_user SET is_admin = 1 WHERE id_user = :id_user");
        return $query->execute(['id_user' => $userId]);
    }

    // Récupérer un utilisateur par nom
    public function getUserByName($name)
    {
        $query = $this->db->prepare("SELECT * FROM journal_user WHERE nom = :nom"); // Recherche par nom
        $query->execute(['nom' => $name]);
        return $query->fetch();
    }
}
