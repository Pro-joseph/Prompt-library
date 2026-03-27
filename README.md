Prompt Repository - Knowledge Base
Prompt Repository est une plateforme interne de Knowledge Management conçue pour DevGenius Solutions. Elle permet aux développeurs de centraliser, catégoriser et réutiliser des prompts LLM performants afin d'optimiser le workflow de l'agence.

🚀 Fonctionnalités
Côté Développeur
Authentification sécurisée : Inscription et connexion pour accéder à la bibliothèque.

Gestion des Prompts : Création, lecture, modification et suppression (CRUD) de vos meilleurs prompts.

Organisation : Attribution d'un titre et d'une catégorie (Code, Marketing, DevOps, etc.) à chaque entrée.

Filtrage intelligent : Recherche de prompts par thématique pour un accès rapide.

Côté Administrateur
Gestion des catégories : Ajout ou modification des thématiques disponibles.

Statistiques : Visualisation des contributeurs les plus actifs de l'agence.

🛠️ Stack Technique
Backend : PHP 8.x (Architecture procédurale propre avec PDO).

Base de données : MySQL (Schéma relationnel normalisé).

Frontend : HTML5, CSS3 (Bootstrap 5 pour une interface moderne et responsive).

Sécurité :

password_hash() pour le hachage des mots de passe.

Requêtes préparées (Prepared Statements) contre les injections SQL.

Gestion des sessions pour la protection des routes.

📊 Schéma de la Base de Données
L'architecture repose sur trois tables principales liées par des clés étrangères :

users : Stocke les informations des développeurs (id, username, email, password).

categories : Liste des thématiques disponibles (id, name).

prompts : Stocke le contenu technique (id, title, content, user_id, category_id, created_at).

⚙️ Installation
Cloner le projet :

Bash
git clone https://github.com/Pro-joseph/Prompt-library.git

Configuration de la base de données :
<img width="756" height="526" alt="Screenshot (32)" src="https://github.com/user-attachments/assets/42d6af15-3364-46e5-9f51-7882a12e45f0" />

Configurer les accès dans le fichier database/db.php.

Lancement :

Placer le dossier dans votre serveur local (XAMPP, WAMP, Laragon).

Accéder à localhost/prompt-library via votre navigateur.

