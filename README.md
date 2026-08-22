# Mini-Gestionnaire de Chantiers — Test technique J4R

Application web permettant de gérer des chantiers et les équipements qui leur sont assignés.

## Stack technique

- **Backend** : Symfony 6.4 (LTS) / PHP 8.1+
- **Base de données** : MySQL 8 + Doctrine ORM
- **Frontend** : Twig, Bootstrap 5 (CDN), JavaScript vanilla (Fetch API)

## Prérequis

- PHP >= 8.1 avec les extensions `ctype`, `iconv`, `pdo_mysql`
- Composer
- MySQL 8 (ou via Docker, voir plus bas)
- Symfony CLI (recommandé) ou le serveur PHP intégré

## Installation

### 1. Cloner le dépôt

```bash
git clone <url-du-depot>
cd <nom-du-dossier>
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer la base de données

Copier `.env` en `.env.local` et adapter `DATABASE_URL` :

```bash
cp .env .env.local
```
