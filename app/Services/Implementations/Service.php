<?php
namespace App\Services\Implementations;

use App\Repositories\Contracts\IRepository;
use App\Services\Contracts\IService;

abstract class Service implements IService {
    protected IRepository $repository;
    public function __construct($repository) {
        $this->repository = $repository;
    }
    public function getAll(array $options = []) {
        return $this->repository->getAll($options);
    }
    public function getById(int $id) {
        return $this->repository->getById($id);
    }

    public function getBy(array $criteria, array $options = []) {
        return $this->repository->getBy($criteria, $options);
    }
    public function create(array $data) {
        return $this->repository->create($data);
    }
    public function update(int $id, array $data) {
        return $this->repository->update($id, $data);
    }
    public function createOrUpdate(array $criteria, array $data) {
        return $this->repository->createOrUpdate($criteria, $data);
    }
    public function delete(int $id) {
        return $this->repository->delete($id);
    }

    public function count(array $criteria = []): int {
        return $this->repository->count($criteria);
    }

    public function autoComplete(string $term, ?string $column = 'name', array $selectedColumns = ['*'], array $options = []) {
        return $this->repository->autoComplete($term, $column, $selectedColumns, $options);
    }

    public function autoCompleteCount(string $keyword, string|null $column = 'name'): int {
        return $this->repository->autoCompleteCount($keyword, $column);
    }
}
