<?php
namespace App\Services\Implementations\Course;

use App\Repositories\Contracts\Course\ICourseRepository;
use App\Services\Contracts\Course\ICourseService;
use App\Services\Implementations\Service;
class CourseService extends Service implements ICourseService {

    protected ICourseRepository $courseRepository;
    public function __construct(ICourseRepository $repository) {
        parent::__construct($repository);
        $this->courseRepository = $repository;
    }

    public function filter(array $data) {
        return $this->courseRepository->filter($data);
    }

    public function filterCount(array $data) {
        return $this->courseRepository->filterCount($data);
    }
}