<?php
namespace App\Services\Implementations\Lesson;

use App\Repositories\Contracts\Lesson\ILessonMediaRepository;
use App\Services\Contracts\Lesson\ILessonMediaService;
use App\Services\Implementations\Service;
class LessonMediaService extends Service implements ILessonMediaService {
    public function __construct(ILessonMediaRepository $repository) {
        parent::__construct($repository);
    }
}