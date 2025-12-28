<?php
namespace App\Repositories\Implementations\Lesson;

use App\Models\Lesson;
use App\Repositories\Contracts\Lesson\ILessonRepository;
use App\Repositories\Implementations\Repository;
class LessonRepository extends Repository implements ILessonRepository {
    public function __construct(Lesson $model) {
        parent::__construct($model);
    }
}