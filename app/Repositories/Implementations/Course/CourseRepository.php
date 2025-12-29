<?php
namespace App\Repositories\Implementations\Course;

use App\Models\Course;
use App\Repositories\Contracts\Course\ICourseRepository;
use App\Repositories\Implementations\Repository;
use Illuminate\Database\Eloquent\Builder;
class CourseRepository extends Repository implements ICourseRepository {
    public function __construct(Course $model) {
        parent::__construct($model);
    }

    public function filter(array $filters, array $options = [])
    {
        $query = $this->applyFilters($this->model->newQuery(), $filters);
        return $this->handleQueryOption($query, $options);
    }

    public function filterCount(array $filters): int
    {
        $query = $this->applyFilters($this->model->newQuery(), $filters);
        return $query->count();
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['keyword'])) {
            $keyword = trim($filters['keyword']);
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('code', 'LIKE', "%{$keyword}%")
                  ->orWhere('slug', 'LIKE', "%{$keyword}%");
            });
        }

        if (!empty($filters['training_center_id'])) {
            $query->where('training_center_id', $filters['training_center_id']);
        }

        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['min_fee'])) {
            $query->where('tuition_fee', '>=', (float)$filters['min_fee']);
        }

        if (isset($filters['max_fee'])) {
            $query->where('tuition_fee', '<=', (float)$filters['max_fee']);
        }

        if (!empty($filters['teacher_id'])) {
            $query->whereHas('teachers', fn ($q) =>
                $q->where('teachers.id', $filters['teacher_id'])
            );
        }

        if (array_key_exists('has_media', $filters)) {
            $filters['has_media']
                ? $query->whereHas('media')
                : $query->whereDoesntHave('media');
        }

        $sort = $filters['sort'] ?? null;
        if (!empty($sort)) {
            switch ($sort) {
                case 'fee_asc':
                    $query->orderBy('tuition_fee', 'asc');
                    break;
                case 'fee_desc':
                    $query->orderBy('tuition_fee', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'start_date_asc':
                    $query->orderBy('start_date', 'asc');
                    break;
                case 'start_date_desc':
                    $query->orderBy('start_date', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        return $query;
    }
}