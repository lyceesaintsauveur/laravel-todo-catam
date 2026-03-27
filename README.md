# 📝 Application Todo - Laravel

## 📌 Présentation

Ce projet est une application web de gestion de tâches (Todo List) développée avec le framework **Laravel**.

Elle permet à un utilisateur authentifié de :
- créer, modifier et supprimer des tâches
- organiser ses tâches par **listes**
- associer des **catégories**
- rechercher des tâches
- gérer son compte utilisateur

## 🎯 Objectifs pédagogiques

Ce projet s’inscrit dans un contexte de formation **BTS SIO** et vise à mettre en œuvre :

- le framework Laravel (MVC)
- la gestion de base de données avec Eloquent ORM
- l’authentification (Laravel Breeze)
- la sécurisation des accès
- les tests automatisés
- l’intégration continue (CI)

## 🛠️ Technologies utilisées

- PHP 8.x
- Laravel 12
- MySQL
- Vite
- Bootstrap (SCSS)
- Laravel Breeze (authentification)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=sofaugeras_laravel-todo&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=sofaugeras_laravel-todo)

## ⚙️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/UTILISATEUR/NOM_DU_REPO.git
cd NOM_DU_REPO
```

2. Installer les dépendances
```bash
composer install
npm install
4. Configuration
cp .env.example .env
php artisan key:generate
```
Configurer la base de données dans le fichier .env

4. Migration et données
```bash
php artisan migrate --seed
6. Lancer le projet
php artisan serve
npm run dev
```
Accès : http://localhost:8000

🔐 Authentification

L’application utilise Laravel Breeze.

Fonctionnalités : inscription, connexion, déconnexion, réinitialisation de mot de passe

## 🗄️ Modélisation
- Relations principales :
- Un utilisateur possède plusieurs tâches
- Une tâche appartient à une liste
- Une tâche peut avoir plusieurs catégories (relation many-to-many)

## 🔍 Fonctionnalités principales

- CRUD des tâches
- gestion des listes
- gestion des catégories
- recherche de tâches
- interface sécurisée
- validation des formulaires

## 🧪 Tests

Lancer les tests : ```php artisan test``` -->  Couverture minimale attendue : 80%

## 🔒 Sécurité

+ protection CSRF
+ validation des entrées utilisateurs
+ middleware d’authentification
+ limitation des tentatives de connexion (throttle)

## 🚀 Intégration continue

Le projet utilise GitHub Actions pour exécuter les tests, vérifier la qualité du code

## 📂 Structure du projet

app/
database/
resources/
routes/
tests/

## 👩‍💻 Auteur 
Projet réalisé dans le cadre du BTS SIO, sous 📄 Licence MIT sur une idée originale de valentin Brosseau
Projet pédagogique – usage académique uniquement.
