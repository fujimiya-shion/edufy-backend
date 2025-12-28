<?php
namespace App\Services\Implementations\Lesson;

use App\Repositories\Contracts\Lesson\ILessonRepository;
use App\Services\Contracts\Lesson\ILessonService;
use App\Services\Implementations\Service;
class LessonService extends Service implements ILessonService {
    public function __construct(ILessonRepository $repository) {
        parent::__construct($repository);
    }
}