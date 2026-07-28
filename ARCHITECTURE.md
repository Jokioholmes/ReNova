# Architecture Technique — ReNova

## Vue d'ensemble

ReNova est un **monorepo fullstack** séparant nettement backend et frontend.

┌─────────────────────────────────────────┐
│ Frontend (Next.js + React) │
│ http://localhost:3000 │
└──────────────┬──────────────────────────┘
│ HTTP/REST
┌──────────────▼──────────────────────────┐
│ Backend API (Laravel) │
│ http://localhost:8000/api │
└──────────────┬──────────────────────────┘
│ SQL/Redis
┌──────────────▼──────────────────────────┐
│ PostgreSQL + Redis │
│ Données persistantes + Cache │
└─────────────────────────────────────────┘


---

## Backend — Laravel 11

### Stack

- **Framework** : Laravel 11
- **API** : REST avec Laravel Sanctum (authentification)
- **Base de données** : PostgreSQL 17
- **Cache/Queue** : Redis 7.4
- **Auth** : JWT via Sanctum
- **Storage** : S3/Cloudinary pour images

### Structure

apps/backend/
├── app/
│ ├── Models/ → Entités Eloquent
│ ├── Http/
│ │ ├── Controllers/
│ │ └── Requests/ → Form Requests validées
│ ├── Services/ → Logique métier
│ ├── Policies/ → Autorisation
│ └── Events/ → Event-driven architecture
├── routes/
│ └── api.php → Routes API
├── database/
│ ├── migrations/
│ └── factories/ → Factories de test
├── config/ → Configuration
└── tests/ → Tests PHPUnit + Pest


### Principes

- **Clean Architecture** : Séparation Services/Controllers
- **Repository Pattern** : Abstraction des requêtes DB
- **Policy Pattern** : Autorisation granulaire
- **Events & Queues** : Tâches asynchrones
- **DTO** : Data Transfer Objects typés
- **Validation stricte** : Côté serveur systématiquement

---

## Frontend — Next.js 15 + React 19

### Stack

- **Framework** : Next.js 15 (App Router)
- **UI** : React 19 + TypeScript
- **Styling** : Tailwind CSS v4
- **Components** : shadcn/ui
- **State** : TanStack Query (data) + React Context (UI state)
- **Forms** : React Hook Form + Zod (validation)
- **Animations** : Framer Motion
- **Icons** : Lucide React

### Structure

apps/frontend/
├── app/
│ ├── (auth)/ → Routes authentification
│ ├── (dashboard)/ → Routes authentifiées
│ ├── (marketplace)/ → Routes publiques
│ └── layout.tsx → Layout racine
├── components/
│ ├── ui/ → Composants réutilisables (shadcn)
│ ├── shared/ → Composants métier partagés
│ └── page-specific/ → Composants spécifiques pages
├── lib/
│ ├── api.ts → Client API
│ ├── hooks/ → React Hooks custom
│ └── utils.ts → Utilitaires
├── types/ → Types TypeScript centralisés
├── styles/ → Styles globaux
└── tests/ → Tests Vitest + Playwright


### Principes

- **Mobile-first** : Conception mobile avant desktop
- **Server Components** par défaut, Client Components si interactivité
- **API Routes** pour proxy backend (CORS, secrets)
- **ISR** (Incremental Static Regeneration) quand pertinent
- **Image Optimization** avec next/image
- **Code Splitting** automatique
- **SEO** : metadata, Open Graph, sitemap

---

## Communication Frontend ↔ Backend

### Flux

1. Frontend appelle **API Backend** via `/api/` (API Routes)
2. API Routes font proxy vers Laravel
3. Laravel traite, retourne JSON
4. Frontend met à jour TanStack Query cache
5. React re-render avec données

### Exemple

```typescript
// Frontend : apps/frontend/lib/api.ts
export async function createListing(data: ListingInput) {
  const response = await fetch('/api/listings', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  return response.json();
}

// Backend : apps/backend/routes/api.php
Route::post('/listings', [ListingController::class, 'store']);

// Backend : apps/backend/app/Http/Controllers/ListingController.php
public function store(StoreListingRequest $request)
{
    $listing = $this->listingService->create($request->validated());
    return new ListingResource($listing);
}
```

---

## Packages Partagés

### `packages/shared/`

Types et constants partagées entre frontend et backend :

packages/shared/
├── types/
│ ├── listing.ts
│ ├── user.ts
│ └── api-response.ts
└── constants/
├── endpoints.ts
└── validation-rules.ts


---

## Base de données

### Modèle (haute niveau)

```sql
-- Users
users (id, email, name, avatar, type: [particulier, boutique, revendeur, reparateur, technicien])

-- Listings (annonces)
listings (id, user_id, title, description, price, images[], condition, status)

-- Transactions
transactions (id, buyer_id, seller_id, listing_id, status, amount)

-- Services (réparation)
repair_services (id, technician_id, listing_id, price, status)

-- Reviews
reviews (id, reviewer_id, reviewee_id, rating, comment)
```

Migrations versionnées dans `apps/backend/database/migrations/`

---

## Sécurité

- ✅ **Validation** : Côté client ET serveur
- ✅ **Auth** : JWT via Sanctum
- ✅ **CORS** : Configuré strictement
- ✅ **CSRF** : Tokens Laravel automatiques
- ✅ **Rate Limiting** : Par endpoint
- ✅ **SQL Injection** : Eloquent ORM (paramétrés)
- ✅ **XSS** : React échappe par défaut, CSP headers
- ✅ **Secrets** : .env jamais versionné

---

## Performance

- ✅ **API Caching** : Redis (queries fréquentes)
- ✅ **Image Optimization** : Next Image + Cloudinary
- ✅ **Pagination** : Toujours, pas de "fetch all"
- ✅ **Lazy Loading** : Frontend par défaut
- ✅ **Code Splitting** : Next.js automatique
- ✅ **DB Indexing** : Sur ID, foreign keys, recherches

---

## Déploiement (futur)

- **Frontend** : Vercel (Next.js optimisé)
- **Backend** : Railway, Heroku ou VPS
- **DB** : Managed PostgreSQL (Railway, Supabase)
- **Cache** : Managed Redis
- **Storage** : AWS S3 ou Cloudinary

---

## Outils Dev

- **Version Control** : Git + GitHub
- **Package Manager** : pnpm (frontend), Composer (backend)
- **Code Quality** : ESLint + Prettier (frontend), PHP CS Fixer (backend)
- **Testing** : Vitest + Playwright (frontend), PHPUnit + Pest (backend)
- **Logging** : Sentry (erreurs), Laravel Logs (backend)
- **Monitoring** : À définir

---

## Roadmap Technique

1. ✅ Initialisation monorepo
2. ⏳ Authentification (Sanctum + JWT)
3. ⏳ CRUD Listings
4. ⏳ Système de recherche + filtres
5. ⏳ Profils utilisateurs
6. ⏳ Avis et notations
7. ⏳ Services de réparation
8. ⏳ Système d'estimation IA
9. ⏳ Paiement (Stripe)
10. ⏳ Notifications temps réel (WebSocket)

---

Dernière mise à jour : 28 juillet 2026