<?php
namespace App\Repositories\Implementations\Teacher;

use App\Models\Teacher;
use App\Repositories\Cache\Behavior\ShouldCache;
use App\Repositories\Contracts\Teacher\ITeacherRepository;
use App\Repositories\Implementations\Repository;
class TeacherRepository extends Repository implements ITeacherRepository, ShouldCache {
    public function __construct(Teacher $model) {
        parent::__construct($model);
    }
}