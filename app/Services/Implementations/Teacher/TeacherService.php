<?php
namespace App\Services\Implementations\Teacher;

use App\Repositories\Contracts\Teacher\ITeacherRepository;
use App\Services\Contracts\Teacher\ITeacherService;
use App\Services\Implementations\Service;
class TeacherService extends Service implements ITeacherService {
    public function __construct(ITeacherRepository $repository) {
        parent::__construct($repository);
    }

}