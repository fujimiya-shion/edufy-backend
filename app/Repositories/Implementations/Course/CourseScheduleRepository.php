<?php
namespace App\Repositories\Implementations\Course;

use App\Models\CourseSchedule;
use App\Repositories\Contracts\Course\ICourseScheduleRepository;
use App\Repositories\Implementations\Repository;
class CourseScheduleRepository extends Repository implements ICourseScheduleRepository {
    public function __construct(CourseSchedule $model) {
        parent::__construct($model);
    }
}