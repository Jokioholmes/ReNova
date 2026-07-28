/**
 * types/index.ts
 * 
 * Tous les types TypeScript centralisés pour le frontend.
 * Source unique de vérité pour l'API shape.
 */

// ===== User Types =====
export type UserType = 'particulier' | 'boutique' | 'revendeur' | 'reparateur' | 'technicien';

export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  avatar_url?: string;
  bio?: string;
  user_type: UserType;
  is_verified: boolean;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export interface AuthCredentials {
  email: string;
  password: string;
}

export interface RegisterCredentials extends AuthCredentials {
  name: string;
  user_type?: UserType;
  phone?: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}

// ===== API Response Types =====
export interface ApiResponse<T = unknown> {
  success: boolean;
  data?: T;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

// ===== Listing Types (à développer) =====
export interface Listing {
  id: number;
  user_id: number;
  title: string;
  description: string;
  price: number;
  images: string[];
  condition: 'new' | 'excellent' | 'good' | 'fair';
  status: 'active' | 'sold' | 'archived';
  created_at: string;
  updated_at: string;
}

// ===== Error Types =====
export interface ApiError {
  message: string;
  status: number;
  errors?: Record<string, string[]>;
}