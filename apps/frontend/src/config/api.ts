/**
 * config/api.ts
 * 
 * Configuration centralisée de l'API backend.
 */

export const API_CONFIG = {
  // URL de base du backend
  BASE_URL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000',

  // Endpoints API
  ENDPOINTS: {
    // Auth
    AUTH: {
      LOGIN: '/api/auth/login',
      REGISTER: '/api/auth/register',
      LOGOUT: '/api/auth/logout',
      ME: '/api/auth/me',
      REFRESH: '/api/auth/refresh',
    },

    // Users
    USERS: {
      LIST: '/api/users',
      DETAIL: (id: number) => `/api/users/${id}`,
      UPDATE: (id: number) => `/api/users/${id}`,
      DELETE: (id: number) => `/api/users/${id}`,
    },

    // Listings (à développer)
    LISTINGS: {
      LIST: '/api/listings',
      DETAIL: (id: number) => `/api/listings/${id}`,
      CREATE: '/api/listings',
      UPDATE: (id: number) => `/api/listings/${id}`,
      DELETE: (id: number) => `/api/listings/${id}`,
    },
  },

  // Timeouts
  TIMEOUT: 30000, // 30 secondes

  // Retry config
  RETRY: {
    COUNT: 3,
    DELAY: 1000,
  },
} as const;

export type ApiConfig = typeof API_CONFIG;