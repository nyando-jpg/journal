INSERT INTO journal_user ( nom, mdp, mail,is_admin)
VALUES
    ( 'Admin', 'adminpass','admin@agence.com',  1),
    ( 'User', 'userpass', 'user@agence.com', 0);

-- Données d'exemple pour la table journal_info
INSERT INTO journal_info (date, details)
VALUES
    ('2026-03-27 14:30:00', '<h1>Premier article du journal</h1>'),
    ('2026-03-26 10:15:00', '<h1>Initialisation du système</h1>'),
    ('2026-03-25 09:00:00', '<h1>Mise en place de la base de données</h1>');
