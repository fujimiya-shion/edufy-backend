<?php
namespace App\Services\Implementations\Course;

use App\Repositories\Contracts\Course\ICourseScheduleRepository;
use App\Services\Contracts\Course\ICourseScheduleService;
use App\Services\Implementations\Service;
class CourseScheduleService extends Service implements ICourseScheduleService {
    public function __construct(ICourseScheduleRepository $repository) {
        parent::__construct($repository);
    }
}