<?php
namespace App\Repositories\Implementations\Course;

use App\Models\Course;
use App\Repositories\Contracts\Course\ICourseRepository;
use App\Repositories\Implementations\Repository;
class CourseRepository extends Repository implements ICourseRepository {
    public function __construct(Course $model) {
        parent::__construct($model);
    }
}