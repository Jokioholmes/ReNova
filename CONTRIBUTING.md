# Guide de Contribution — ReNova

Merci de contribuer à ReNova ! Ce document explique comment bien travailler sur le projet.

---

## 🎯 Principes généraux

- **Code propre** : SOLID, DRY, KISS
- **Commits atomiques** : Une fonctionnalité = un commit
- **Convention Commits** : Messages clairs et structurés
- **Tests** : Toute nouvelle fonctionnalité doit avoir des tests
- **Documentation** : Explique le *pourquoi*, pas le *comment*

---

## 📋 Workflow

### 1. Créer une feature branch

```bash
git checkout main
git pull origin main
git checkout -b feat/nom-de-la-feature
```

Formats de branche acceptés :
- `feat/` → Nouvelle fonctionnalité
- `fix/` → Correction de bug
- `refactor/` → Refactoring sans changement fonctionnel
- `chore/` → Mise à jour dépendances, config
- `docs/` → Documentation uniquement

### 2. Développer

- Fais tes changements
- Teste localement
- Assure-toi que les tests passent

```bash
# Backend
cd apps/backend
composer test

# Frontend
cd apps/frontend
pnpm test
pnpm lint
```

### 3. Commit avec Conventional Commits

Format : `type(scope): message`

Exemples :

```bash
# Nouvelle fonctionnalité
git commit -m "feat(auth): add JWT token refresh endpoint"

# Correction bug
git commit -m "fix(listings): resolve image upload error"

# Refactoring
git commit -m "refactor(api): simplify listing service"

# Documentation
git commit -m "docs(readme): add database schema diagram"

# Dépendances
git commit -m "chore(deps): upgrade Laravel to 11.15"
```

Types acceptés :
- `feat` → Nouvelle fonctionnalité
- `fix` → Correction bug
- `refactor` → Refactoring
- `docs` → Documentation
- `chore` → Maintenance
- `style` → Formatage (pas de logique)
- `test` → Ajout/modification tests
- `perf` → Optimisation performance

### 4. Push et Pull Request

```bash
git push origin feat/nom-de-la-feature
```

Ensuite crée une **Pull Request** sur GitHub avec :
- **Titre clair** reprenant le commit
- **Description** expliquant quoi et pourquoi
- **Linked issues** si applicable

### 5. Code Review & Merge

Une fois approuvée, merge sur `main` via GitHub (squash ou rebase).

---

## 🧪 Tests obligatoires

### Backend (PHPUnit + Pest)

```bash
cd apps/backend
./vendor/bin/pest
```

Coverage minimum : **80%**

### Frontend (Vitest + Playwright)

```bash
cd apps/frontend
pnpm test          # Tests unitaires
pnpm test:e2e      # Tests end-to-end
```

---

## 📏 Style de code

### Backend (Laravel)

- **PSR-12** : Laravel Pint applique automatiquement
- Longueur max ligne : **120 caractères**
- Indentation : **4 espaces**

```bash
cd apps/backend
./vendor/bin/pint
```

### Frontend (React/TypeScript)

- **ESLint** : Règles strictes
- **Prettier** : Formatage automatique
- Longueur max ligne : **100 caractères**
- Indentation : **2 espaces**

```bash
cd apps/frontend
pnpm lint           # Détecte erreurs
pnpm format         # Applique Prettier
```

---

## 📝 Checklist avant push

- ✅ Tests locaux passent
- ✅ Pas de console.log() ou var_dump()
- ✅ Code formaté (lint + prettier)
- ✅ Types TypeScript corrects (frontend)
- ✅ Pas de secrets (.env, API keys)
- ✅ Commit message suit Conventional Commits
- ✅ Branch nommée correctement

---

##  Signaler un bug

1. Ouvre une **Issue** sur GitHub
2. Titre clair + reproduction steps
3. Version du système, navigateur, environnement
4. Screenshot ou logs si pertinent

---

## 💡 Suggestions / Améliorations

Ouvre une **Discussion** sur GitHub (pas une Issue). On en parlera avant de développer.

---

## ❓ Questions

Consulte d'abord :
- [Architecture.md](./ARCHITECTURE.md)
- Issues/Discussions GitHub
- Contacte directement

---

Merci ! 🙏