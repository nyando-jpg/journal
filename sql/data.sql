USE gestion_the;

INSERT INTO journal_categories (nom_categorie)
VALUES
    ('Geopolitique'),
    ('Front'),
    ('Humanitaire'),
    ('Infrastructure')
ON DUPLICATE KEY UPDATE nom_categorie = VALUES(nom_categorie);

INSERT INTO journal_user ( nom, mdp, mail,is_admin)
VALUES
    ( 'Admin', 'adminpass','admin@agence.com',  1),
    ( 'User', 'userpass', 'user@agence.com', 0);

-- Données d'exemple pour la table journal_info
INSERT INTO journal_info (date, id_admin, id_categorie, titre, details)
VALUES
    ('2026-03-27 14:30:00', 1, 1, 'Premier article du journal', '<h1>Premier article du journal</h1><p>Contenu d exemple.</p>'),
    ('2026-03-26 10:15:00', 1, 2, 'Initialisation du systeme', '<h1>Initialisation du systeme</h1><p>Contenu d exemple.</p>'),
    ('2026-03-25 09:00:00', 1, 3, 'Mise en place de la base de donnees', '<h1>Mise en place de la base de donnees</h1><p>Contenu d exemple.</p>');
