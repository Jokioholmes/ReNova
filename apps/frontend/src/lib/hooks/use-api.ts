/**
 * lib/hooks/use-api.ts
 * 
 * Hook générique pour les requêtes API avec React Query.
 */

'use client';

import { useQuery, useMutation, UseQueryResult, UseMutationResult } from '@tanstack/react-query';
import { apiClient } from '@/lib/api-client';
import { ApiError } from '@/types';
import { AxiosRequestConfig } from 'axios';

/**
 * useApi - Hook pour GET requests
 */
export function useApi<T>(
  url: string | null,
  config?: AxiosRequestConfig,
  options?: any
): UseQueryResult<T, ApiError> {
  return useQuery({
    queryKey: [url],
    queryFn: () => apiClient.get<T>(url!),
    enabled: !!url,
    ...options,
  });
}

/**
 * usePostApi - Hook pour POST/PATCH requests
 */
export function usePostApi<T>(
  url: string,
  options?: any
): UseMutationResult<T, ApiError, unknown, unknown> {
  return useMutation({
    mutationFn: (data: unknown) => apiClient.post<T>(url, data),
    ...options,
  });
}

/**
 * usePatchApi - Hook pour PATCH requests
 */
export function usePatchApi<T>(
  url: string,
  options?: any
): UseMutationResult<T, ApiError, unknown, unknown> {
  return useMutation({
    mutationFn: (data: unknown) => apiClient.patch<T>(url, data),
    ...options,
  });
}

/**
 * useDeleteApi - Hook pour DELETE requests
 */
export function useDeleteApi<T>(
  url: string,
  options?: any
): UseMutationResult<T, ApiError, void, unknown> {
  return useMutation({
    mutationFn: () => apiClient.delete<T>(url),
    ...options,
  });
}