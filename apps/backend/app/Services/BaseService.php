<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

/**
 * BaseService
 * 
 * Classe mère pour tous les services métier.
 * Centralise la logique commune et les patterns réutilisables.
 */
abstract class BaseService
{
    /**
     * Modèle Eloquent associé
     */
    protected Model $model;

    /**
     * Constructeur du service
     */
    public function __construct()
    {
        $this->model = $this->getModel();
    }

    /**
     * Retourner le modèle associé
     */
    abstract protected function getModel(): Model;

    /**
     * Récupérer tous les enregistrements avec pagination
     */
    public function paginate(int $perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Récupérer un enregistrement par ID
     */
    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Créer un nouvel enregistrement
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Mettre à jour un enregistrement
     */
    public function update(int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record->fresh();
    }

    /**
     * Supprimer un enregistrement
     */
    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Récupérer selon des critères
     */
    public function findWhere(array $criteria)
    {
        $query = $this->model->query();

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }
}