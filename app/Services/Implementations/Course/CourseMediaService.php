<?php
namespace App\Services\Implementations\Course;

use App\Repositories\Contracts\Course\ICourseMediaRepository;
use App\Services\Contracts\Course\ICourseMediaService;
use App\Services\Implementations\Service;
class CourseMediaService extends Service implements ICourseMediaService {
    public function __construct(ICourseMediaRepository $repository) {
        parent::__construct($repository);
    }
}