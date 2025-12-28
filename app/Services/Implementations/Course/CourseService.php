<?php
namespace App\Services\Implementations\Course;

use App\Repositories\Contracts\Course\ICourseRepository;
use App\Services\Contracts\Course\ICourseService;
use App\Services\Implementations\Service;
class CourseService extends Service implements ICourseService {
    public function __construct(ICourseRepository $repository) {
        parent::__construct($repository);
    }
}