<?php
namespace App\Repositories\Contracts;
interface IRepository {
    public function getAll(array $options = []);
    public function getById(int $id);
    public function getBy(array $criteria, array $options = []);
    public function create(array $data);
    public function update(int $id, array $data);
    public function createOrUpdate(array $criteria, array $data);
    public function delete(int $id, bool $softDelete = true);
    public function count(array $criteria = []): int;
    public function autoComplete(string $keyword, ?string $column = 'name', array $selectedColumns = ['*'], array $options = []);
    public function autoCompleteCount(string $keyword, ?string $column = 'name'): int;
}
