<?php


namespace NextLevelHub\Services;

use NextLevelHub\Models\Usuario;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\UsuarioRepository;

class UsuarioService
{
    private UsuarioRepository $repository;

    public function __construct(BaseDatos $db)
    {
        $this->repository = new UsuarioRepository($db);
    }

    public function findAll(): array
    {
        return $this->repository->findAll() ?? [];
    }

    public function save(Usuario $usuario): bool
    {
        return $this->repository->save($usuario);
    }

    public function update(Usuario $usuario): bool
    {
        return $this->repository->update($usuario);
    }
}