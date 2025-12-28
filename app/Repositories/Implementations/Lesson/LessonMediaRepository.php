<?php
namespace App\Repositories\Implementations\Lesson;

use App\Models\LessonMedia;
use App\Repositories\Contracts\Lesson\ILessonMediaRepository;
use App\Repositories\Implementations\Repository;
class LessonMediaRepository extends Repository implements ILessonMediaRepository {
    public function __construct(LessonMedia $model) {
        parent::__construct($model);
    }
}