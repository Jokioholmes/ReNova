/**
 * lib/hooks/use-auth.ts
 * 
 * Hook pour gérer l'authentification.
 */

'use client';

import { useState, useCallback, useEffect } from 'react';
import { User, AuthCredentials, RegisterCredentials, AuthResponse } from '@/types';
import { apiClient } from '@/lib/api-client';
import { API_CONFIG } from '@/config/api';

interface UseAuthReturn {
  user: User | null;
  isLoading: boolean;
  error: string | null;
  login: (credentials: AuthCredentials) => Promise<void>;
  register: (credentials: RegisterCredentials) => Promise<void>;
  logout: () => void;
  isAuthenticated: boolean;
}

/**
 * Hook useAuth
 * 
 * Gère l'état d'authentification et les opérations (login, register, logout).
 */
export function useAuth(): UseAuthReturn {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  // Initialiser l'auth depuis le token sauvegardé
  useEffect(() => {
    const initAuth = async () => {
      const token = apiClient.getToken();
      if (token) {
        try {
          setIsLoading(true);
          const currentUser = await apiClient.get<User>(API_CONFIG.ENDPOINTS.AUTH.ME);
          setUser(currentUser);
        } catch (err) {
          apiClient.clearToken();
          setUser(null);
        } finally {
          setIsLoading(false);
        }
      }
    };

    initAuth();
  }, []);

  const login = useCallback(async (credentials: AuthCredentials) => {
    try {
      setIsLoading(true);
      setError(null);

      const response = await apiClient.post<AuthResponse>(
        API_CONFIG.ENDPOINTS.AUTH.LOGIN,
        credentials
      );

      apiClient.setToken(response.token);
      setUser(response.user);
    } catch (err: any) {
      const message = err.message || 'Erreur de connexion';
      setError(message);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, []);

  const register = useCallback(async (credentials: RegisterCredentials) => {
    try {
      setIsLoading(true);
      setError(null);

      const response = await apiClient.post<AuthResponse>(
        API_CONFIG.ENDPOINTS.AUTH.REGISTER,
        credentials
      );

      apiClient.setToken(response.token);
      setUser(response.user);
    } catch (err: any) {
      const message = err.message || 'Erreur d\'inscription';
      setError(message);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, []);

  const logout = useCallback(() => {
    apiClient.clearToken();
    setUser(null);
    setError(null);
  }, []);

  return {
    user,
    isLoading,
    error,
    login,
    register,
    logout,
    isAuthenticated: !!user,
  };
}