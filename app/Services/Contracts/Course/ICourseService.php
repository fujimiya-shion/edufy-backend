<?php
namespace App\Services\Contracts\Course;

use App\Services\Contracts\IService;
interface ICourseService extends IService {
    public function filter(array $data);
    public function filterCount(array $data);
}