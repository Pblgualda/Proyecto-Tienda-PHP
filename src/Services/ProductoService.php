<?php

namespace NextLevelHub\Services;

use NextLevelHub\Models\Producto;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\ProductoRepository;

class ProductoService
{
    private ProductoRepository $repository;

    public function __construct(BaseDatos $db){
        $this->repository = new ProductoRepository($db);
    }

    public function findAll(): array{
        return $this->repository->findAll() ?? [];
    }

    public function save(Producto $producto): bool{
        return $this->repository->save($producto);
    }

    public function update(Producto $producto): bool{
        return $this->repository->update($producto);
    }
}