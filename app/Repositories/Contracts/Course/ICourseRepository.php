<?php
namespace App\Repositories\Contracts\Course;

use App\Repositories\Contracts\IRepository;
interface ICourseRepository extends IRepository {
    public function filter(array $data);
    public function filterCount(array $data);    
}