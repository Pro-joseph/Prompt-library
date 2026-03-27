<h3>Prompt Repository 🚀</h3> <br>
Knowledge Base 
Prompt Repository est une plateforme interne de Knowledge Management conçue pour DevGenius Solutions. Elle permet aux développeurs de centraliser, catégoriser et réutiliser des prompts LLM performants afin d'optimiser le workflow de l'agence.

🚀 Fonctionnalités
Côté Développeur
- Authentification sécurisée : Inscription et connexion pour accéder à la bibliothèque.
- Gestion des Prompts : Création, lecture, modification et suppression (CRUD) de vos meilleurs prompts.
- Organisation : Attribution d'un titre et d'une catégorie (Code, Marketing, DevOps, etc.) à chaque entrée.
- Filtrage intelligent : Recherche de prompts par thématique pour un accès rapide.

Côté Administrateur
- Gestion des catégories : Ajout ou modification des thématiques disponibles.
- Statistiques : Visualisation des contributeurs les plus actifs de l'agence.

🛠️ Stack Technique
- Backend : PHP 8.x (Architecture procédurale propre avec PDO).
- Base de données : MySQL (Schéma relationnel normalisé).
- Frontend : HTML5, CSS3 (Bootstrap 5 pour une interface moderne et responsive).

Sécurité :

- password_hash() pour le hachage des mots de passe.
- Requêtes préparées (Prepared Statements) contre les injections SQL.
- Gestion des sessions pour la protection des routes.

📊 Schéma de la Base de Données
- L'architecture repose sur trois tables principales liées par des clés étrangères :
- users : Stocke les informations des développeurs (id, username, email, password).
- categories : Liste des thématiques disponibles (id, name).
- prompts : Stocke le contenu technique (id, title, content, user_id, category_id, created_at).

⚙️ Installation
Cloner le projet :

Bash
git clone [https://github.com/Pro-joseph/Prompt-library.git](https://github.com/Pro-joseph/Prompt-library.git)

Configuration de la base de données :
<img width="756" height="526" alt="Screenshot (32)" src="https://github.com/user-attachments/assets/42d6af15-3364-46e5-9f51-7882a12e45f0" />

📸 Preview
<img width="1366" height="768" alt="Screenshot (45)" src="https://github.com/user-attachments/assets/f74088c6-3acf-4341-b381-271ac4ba2296" />
<img width="1366" height="768" alt="Screenshot (44)" src="https://github.com/user-attachments/assets/3e378c1a-e5cc-4d1d-bee1-9ce8742d7255" />
<img width="1366" height="768" alt="Screenshot (43)" src="https://github.com/user-attachments/assets/f68fb843-21e5-4bc2-be55-75d4f4e89376" />
<img width="1366" height="768" alt="Screenshot (42)" src="https://github.com/user-attachments/assets/00e6ce96-7ab8-4aa0-ba91-bf70817d42bd" />
<img width="1366" height="768" alt="Screenshot (41)" src="https://github.com/user-attachments/assets/49406b28-76f2-4388-ad02-68dd861e0733" />
<img width="1366" height="768" alt="Screenshot (40)" src="https://github.com/user-attachments/assets/628383e9-48a0-40b0-a3aa-cc79b9971e20" />
<img width="1366" height="768" alt="Screenshot (39)" src="https://github.com/user-attachments/assets/d24e23be-cd8f-4fa4-921c-4962dacf99d6" />


🧠 What I Learned
During this project, I deepened my understanding of:

How to fetch data / How to built CRUD / How built default components for Dashboard
Creating Mixins and Functions to reduce code duplication (DRY principle).
creating statistics and both client side and admin side with auth.


