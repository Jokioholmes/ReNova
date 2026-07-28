# ReNova

**Marketplace électronique pour l'Afrique francophone**

Connecte particuliers, boutiques, revendeurs, réparateurs et techniciens autour des appareils électroniques.

-  **Vendre** des appareils
-  **Acheter** d'autres appareils
-  **Échanger** entre utilisateurs
-  **Réparer** via des techniciens
-  **Estimer** la valeur d'un appareil

---

## Vision

ReNova ambitionne de devenir la **référence en Afrique francophone** pour le marché de l'électronique d'occasion et des services associés, puis d'évoluer vers un produit **international**.

---

## 🏗️ Architecture

ReNova est un **monorepo** structuré ainsi :

apps/
├── backend/ → API REST Laravel 11
└── frontend/ → Web app Next.js + React

packages/
└── shared/ → Types, constants, utilitaires partagés

docker/ → Configuration Docker Compose
docs/ → Documentation du projet


---

## 📋 Prérequis

- **Node.js** v25.2.1+
- **PHP** 8.3+
- **Composer** 2.9+
- **PostgreSQL** 17+
- **Redis** 7.4+
- **Docker** 29.6+ 
- **Git** 2.51+

---

## 🔧 Installation locale

### 1. Cloner le repo

```bash
git clone https://github.com/Jokioholmes/ReNova.git
cd ReNova
```

### 2. Installer les dépendances

**Backend** :
```bash
cd apps/backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

**Frontend** :
```bash
cd apps/frontend
pnpm install
```

### 3. Lancer le projet

**Backend** :
```bash
cd apps/backend
php artisan serve
```

**Frontend** :
```bash
cd apps/frontend
pnpm dev
```

---

## 🐳 Avec Docker

```bash
docker compose up -d
```

Tous les services démarrent automatiquement.

---

## Documentation

- [Architecture technique](./ARCHITECTURE.md)
- [Guide de contribution](./CONTRIBUTING.md)
- [Changelog](./CHANGELOG.md)

---

## Auteur

**Joël Agbakpem**  
Développeur Web & Mobile | UI/UX Designer  
[GitHub](https://github.com/Jokioholmes)

---

## 📄 Licence

MIT License — Voir [LICENSE](./LICENSE) pour les détails.