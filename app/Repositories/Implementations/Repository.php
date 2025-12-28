<?php
namespace App\Repositories\Implementations;
use App\Events\Cache\Queries\GetModelByCriteria;
use App\Events\Cache\Queries\GetModelById;
use App\Events\Cache\Queries\GetModels;
use App\Events\Cache\Updates\ModelCreated;
use App\Events\Cache\Updates\ModelDeleted;
use App\Events\Cache\Updates\ModelUpdated;
use App\Repositories\Cache\Behavior\ShouldCache;
use App\Repositories\Contracts\IRepository;
use App\Services\Cache\CacheService;
use Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Log;

abstract class Repository implements IRepository
{
    protected Model $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function handleQueryOption(Model|Builder|QueryBuilder $query, $options = [])
    {
        if (!empty($options['with'])) {
            $with = is_array($options['with']) ? $options['with'] : [$options['with']];
            foreach ($with as $relation) {
                $query = $query->with($relation);
            }
        }

        if (!empty($options['perpage'])) {
            $page = $options['page'] ?? 1;

            $limit = $options['perpage'];
            $offset = ($page - 1) * $limit;
            $query = $query->limit($limit)->offset($offset);
        }

        if (!empty($options['orderBy'])) {
            $orderBy = $options['orderBy'];
            foreach ($orderBy as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }
        return $query->get();
    }
    /**
     * Retrieves all records from the database.
     *
     * Supports pagination and relationships.
     *
     * If the repository implements ShouldCache, it will cache the result for the same parameters.
     *
     * @param array $options
     *      Contains the following options:
     *      - `with`: Array of related models to load.
     *      - `perpage`: Number of records to retrieve per page.
     *      - `page`: Current page number.
     *      - `orderBy`: Associative array of columns and their respective directions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(array $options = [])
    {
        $eventInstance = app()->make(GetModels::class, [
            'model' => $this->model,
            'options' => $options
        ]);
        $model = $this->model;
        if ($this instanceof ShouldCache) {
            $cacheService = app(CacheService::class);
            $cacheKey = $eventInstance->getCacheKey();
            $cachedData = $cacheService->get($cacheKey, [class_basename($model)]);
            if ($cachedData)
                return $cachedData;
        }


        $data = $this->handleQueryOption($model, $options);

        if($this instanceof ShouldCache) {
            $eventInstance->setData($data);
            event($eventInstance);
        }
        return $data;
    }


    /**
     * Retrieves a record from the database by its id.
     *
     * If the repository implements ShouldCache, it will cache the result for the same id.
     *
     * @param int $id
     *      The id of the record to retrieve.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     *      The retrieved record, or null if not found.
     */
    public function getById(int $id)
    {
        $eventInstance = app()->make(GetModelById::class, [
            'model' => $this->model,
            'id' => $id,
        ]);

        $model = $this->model;
        if ($this instanceof ShouldCache) {
            $cacheService = app(CacheService::class);
            $cacheKey = $eventInstance->getCacheKey();
            $cachedData = $cacheService->get($cacheKey, [class_basename($model)]);
            if ($cachedData)
                return $cachedData;
        }

        $data = $this->model->find($id);

        if($this instanceof ShouldCache) {
            $eventInstance->setData($data);
            event($eventInstance);
        }
        
        return $data;
    }


    /**
     * Retrieves records from the database by given criteria.
     *
     * If the repository implements ShouldCache, it will cache the result for the same criteria.
     *
     * @param array $criteria
     *      Array of key-value pairs where the key is the column name and the value is the value to search for.
     *      Example: ['name' => 'John Doe', 'age' => 25]
     *
     * @param array $options
     *      Array of options to be passed to the query builder.
     *      Example: ['orderBy' => 'name', 'limit' => 10]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *      The retrieved records.
     */
    public function getBy(array $criteria, array $options = [])
    {
        $eventInstance = app()->make(GetModelByCriteria::class, [
            'model' => $this->model,
            'criteria' => $criteria,
            'options' => $options,
        ]);

        $model = $this->model;
        if ($this instanceof ShouldCache) {
            $cacheService = app(CacheService::class);
            $cacheKey = $eventInstance->getCacheKey();
            $cachedData = $cacheService->get($cacheKey, [class_basename($model)]);
            if ($cachedData)
                return $cachedData;
        }

        $query = $this->model;
        foreach ($criteria as $key => $value) {
            $query = $query->where($key, $value);
        }
        $data = $this->handleQueryOption($query, $options);
        if ($this instanceof ShouldCache) {
            $eventInstance->setData($data);
            event($eventInstance);
        }
        return $data;
    }


    /**
     * Creates a new record in the database.
     *
     * If the repository implements ShouldCache, it will invalidate the cache for the same criteria.
     *
     * @param array $data
     *      Array of key-value pairs where the key is the column name and the value is the value to insert.
     *      Example: ['name' => 'John Doe', 'age' => 25]
     *
     * @return \Illuminate\Database\Eloquent\Model
     *      The created record.
     */
    public function create(array $data)
    {
        $created = $this->model->create($data);
        if ($created) {
            $eventInstance = app()->make(ModelCreated::class, ['model' => $this->model, 'data' => $created]);
            event($eventInstance);
        }
        return $created;
    }


    /**
     * Updates an existing record in the database.
     *
     * @param int $id
     *      The ID of the record to update.
     *
     * @param array $data
     *      Array of key-value pairs where the key is the column name and the value is the value to be updated.
     *      Example: ['name' => 'John Doe', 'age' => 25]
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     *      The updated record, or null if the record does not exist.
     */
    public function update(int $id, array $data)
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->fill($data);
            $updated = $model->update();
            if ($updated) {
                $eventInstance = app()->make(ModelUpdated::class, ['model' => $model, 'data' => $this->model->find($id)]);
                event($eventInstance);
            }
            return $updated;
        }
        return null;
    }


    /**
     * Creates a new record in the database if it does not exist, otherwise updates the existing record.
     *
     * @param array $criteria
     *      Array of key-value pairs where the key is the column name and the value is the value to search for.
     *      Example: ['name' => 'John Doe', 'age' => 25]
     *
     * @param array $data
     *      Array of key-value pairs where the key is the column name and the value is the value to be inserted or updated.
     *      Example: ['name' => 'John Doe', 'age' => 25]
     *
     * @return \Illuminate\Database\Eloquent\Model
     *      The newly created or updated record.
     */
    public function createOrUpdate(array $criteria, array $data)
    {
        $model = $this->getBy($criteria)->first();
        return empty($model) ? $this->create($data) : $this->update($model->id, $data);
    }
    /**
     * Deletes a record from the database.
     *
     * If the $softDelete parameter is true, the record will be soft deleted. Otherwise, it will be permanently deleted.
     *
     * @param int $id
     *      The ID of the record to delete.
     *
     * @param bool $softDelete
     *      Whether to soft delete the record (true) or permanently delete it (false).
     *      Default is true.
     *
     * @return bool
     *      True if the record was deleted, false otherwise.
     */
    public function delete(int $id, bool $softDelete = true)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return false;
        }

        $deleted = $softDelete ? $model->delete() : $model->forceDelete();
        if ($deleted) {
            $eventInstance = app()->make(ModelDeleted::class, ['model' => $model]);
            event($eventInstance);
        }

        return $deleted;
    }


    /**
     * Counts the number of records in the database.
     *
     * If criteria are provided, it will count only the records that match the given criteria.
     * Otherwise, it will count all records in the database.
     *
     * @param array $criteria
     *      An array of key-value pairs where the key is the column name and the value is the value to filter by.
     *      Example: ['status' => 'active']
     *
     * @return int
     *      The count of records matching the criteria, or the total count if no criteria are provided.
     */
    public function count(array $criteria = []): int
    {
        if (!empty($criteria))
            return $this->model->where($criteria)->count();
        return $this->model::query()->count();
    }

    public function autoComplete(string $term, ?string $column = 'name', array $selectedColumns = ['*'], array $options = [])
    {
        $query = $this->model->getQuery();
        $query->where($column, 'LIKE', "%{$term}%");
        $query->orderByRaw("CASE
            WHEN {$column} = ? THEN 1
            WHEN {$column} LIKE ? THEN 2
            ELSE 3
            END", [$term, "{$term}%"]);
        $data = $this->handleQueryOption($query, $options);
        return $data;
    }

    public function autoCompleteCount(string $keyword, string|null $column = 'name'): int {
        $query = $this->model->getQuery();
        $query->where($column, 'LIKE', "%{$keyword}%");
        $query->orderByRaw("CASE
            WHEN {$column} = ? THEN 1
            WHEN {$column} LIKE ? THEN 2
            ELSE 3
            END", [$keyword, "{$keyword}%"]);
        return $query->count();
    }
}
