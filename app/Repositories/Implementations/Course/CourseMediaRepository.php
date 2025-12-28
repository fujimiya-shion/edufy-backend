<?php
namespace App\Repositories\Implementations\Course;

use App\Models\CourseMedia;
use App\Repositories\Contracts\Course\ICourseMediaRepository;
use App\Repositories\Implementations\Repository;
class CourseMediaRepository extends Repository implements ICourseMediaRepository {
    public function __construct(CourseMedia $model) {
        parent::__construct($model);
    }
}