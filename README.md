# Mini-Gestionnaire de Chantiers — Test technique J4R

Application web permettant de gérer des chantiers et les équipements qui leur sont assignés.

## Stack technique

- Backend : Symfony 6.4 (LTS) / PHP 8.1+
- Base de données : MySQL 8 + Doctrine ORM
- Frontend : Twig, Bootstrap 5 (CDN), JavaScript vanilla (Fetch API)

## Prérequis

- PHP >= 8.1 avec les extensions ctype, iconv, pdo_mysql
- Composer
- MySQL 8 (ou via Docker, voir plus bas)
- Symfony CLI (recommandé) ou le serveur PHP intégré

## Installation

### 1. Cloner le dépôt

git clone <url-du-depot>
cd <nom-du-dossier>

### 2. Installer les dépendances PHP

composer install

### 3. Configurer la base de données

Copier .env en .env.local et adapter DATABASE_URL :

cp .env .env.local

DATABASE_URL="mysql://root:root@127.0.0.1:3306/mini_gestionnaire_chantiers?serverVersion=8.0.32&charset=utf8mb4"

### 4. Lancer MySQL

Option A — via Docker (recommandé) :
docker compose up -d database

Option B — MySQL installé en local, en adaptant les identifiants dans .env.local.

### 5. Créer la base et exécuter les migrations

php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate

### 6. Charger les fixtures (jeu de données de test)

php bin/console doctrine:fixtures:load

Cela crée 3 chantiers et 5 équipements, avec des associations entre eux.

### 7. Lancer le serveur

symfony server:start
ou
php -S 127.0.0.1:8000 -t public

Puis ouvrir http://127.0.0.1:8000

## Fonctionnement

- La page d'accueil liste tous les chantiers avec leur statut, adresse, dates et équipements associés.
- Chaque chantier non terminé affiche un bouton "Marquer comme terminé".
- Ce bouton déclenche une requête AJAX (fetch, POST) vers /chantier/{id}/terminer, sans rechargement de page.
- Le serveur répond en JSON ; le JS met à jour le badge de statut et retire le bouton dynamiquement.
- En cas d'erreur (chantier introuvable, déjà terminé, ou serveur injoignable), un message d'alerte s'affiche sans casser la page.

## Choix techniques et justifications

- Enum PHP StatutChantier plutôt qu'un simple string : évite les fautes de frappe sur les valeurs de statut et centralise les valeurs autorisées.
- Relation ManyToMany entre Chantier et Equipement : un équipement peut raisonnablement être utilisé sur plusieurs chantiers, pas uniquement affecté à un seul.
- Champ dateFin en plus de dateDebut : permet d'afficher une fourchette de dates prévisionnelle par chantier.
- Endpoint AJAX dédié qui renvoie du JSON : le front ne doit jamais recevoir de HTML/redirection sur cette route, c'est un point d'API consommé uniquement par fetch().
- Bootstrap 5 via CDN : permet d'aller vite sur le design, sans configuration Webpack/Encore superflue.

## Gestion des erreurs

L'endpoint /chantier/{id}/terminer gère explicitement 3 cas d'erreur :

1. Chantier introuvable : HTTP 404 + message JSON explicite.
2. Chantier déjà terminé : HTTP 409 (conflit).
3. Erreur serveur inattendue : HTTP 500, message générique.

Côté JavaScript, handleTerminer() gère les erreurs réseau et les réponses HTTP non-OK, réactive le bouton et affiche un message clair, sans modifier l'interface tant que la requête n'a pas réellement réussi.

## Structure du projet

src/Entity : Chantier, Equipement
src/Enum : StatutChantier
src/Repository : requêtes personnalisées (findAllWithEquipements)
src/Controller : ChantierController (page + endpoint AJAX)
src/DataFixtures : jeu de données de test
templates/base.html.twig
templates/chantier/index.html.twig
public/js/app.js : logique AJAX
public/css/style.css
