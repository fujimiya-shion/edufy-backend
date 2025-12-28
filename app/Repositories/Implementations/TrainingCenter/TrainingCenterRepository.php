<?php
namespace App\Repositories\Implementations\TrainingCenter;

use App\Models\TrainingCenter;
use App\Repositories\Cache\Behavior\ShouldCache;
use App\Repositories\Contracts\TrainingCenter\ITrainingCenterRepository;
use App\Repositories\Implementations\Repository;
class TrainingCenterRepository extends Repository implements ITrainingCenterRepository, ShouldCache {
    public function __construct(TrainingCenter $model) {
        parent::__construct($model);
    }
}