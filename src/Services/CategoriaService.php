<?php

namespace NextLevelHub\Services;

use NextLevelHub\Models\Categoria;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\CategoriaRepository;

class CategoriaService
{
    private CategoriaRepository $repository;

    public function __construct(BaseDatos $db){
        $this->repository = new CategoriaRepository($db);
    }

    public function findAll(): array{
        return $this->repository->findAll() ?? [];
    }

    public function save(Categoria $categoria): bool{
        return $this->repository->save($categoria);
    }

    public function update(Categoria $categoria): bool{
        return $this->repository->update($categoria);
    }
}