<?php

namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\Categoria;
use RuntimeException;
use PDOException;

class CategoriaRepository implements CategoriaRepositoryInterface
{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array
    {
        try{
            $sql = "SELECT * FROM categorias";
            $this->conexion->ejecutar($sql);

            $categorias = [];
            foreach($this->conexion->extraer_todos() as $fila){
                $categorias[] = Categoria::fromArray($fila);
            }
            return $categorias;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener las categorías: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function create(Categoria $categoria): bool
    {
        try{
            $sql = "INSERT INTO categorias (nombre, descripcion, created_at)
                    VALUES (:nombre, :descripcion, :created_at)";

            $params = [
                ':nombre'      => ['valor' => $categoria->getNombre()],
                ':descripcion' => ['valor' => $categoria->getDescripcion()],
                ':created_at'  => ['valor' => $categoria->getCreated()]
            ];

            $exito = $this->conexion->ejecutar($sql, $params);

            if($exito){
                $nuevoId = $this->conexion->ultimoIdInsertado();
                if($nuevoId > 0){
                    $categoria->setId($nuevoId);
                }
            }

            return $exito;

        } catch(PDOException $e){
            throw new RuntimeException(
                "ERROR AL CREAR UNA NUEVA CATEGORÍA: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function update(Categoria $categoria): bool
    {
        try{
            $sql = "UPDATE categorias 
                    SET nombre = :nombre, descripcion = :descripcion, created_at = :created_at
                    WHERE id = :id";

            $params = [
                ':id'          => ['valor' => $categoria->getId()],
                ':nombre'      => ['valor' => $categoria->getNombre()],
                ':descripcion' => ['valor' => $categoria->getDescripcion()],
                ':created_at'  => ['valor' => $categoria->getCreated()]
            ];

            return $this->conexion->ejecutar($sql, $params);

        } catch(PDOException $e){
            throw new RuntimeException(
                "ERROR AL ACTUALIZAR LA CATEGORÍA: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function save(Categoria $categoria): bool
    {
        return ($categoria->getId() > 0)
            ? $this->update($categoria)
            : $this->create($categoria);
    }
}