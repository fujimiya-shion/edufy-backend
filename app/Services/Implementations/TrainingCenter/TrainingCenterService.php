<?php
namespace App\Services\Implementations\TrainingCenter;

use App\Repositories\Contracts\TrainingCenter\ITrainingCenterRepository;
use App\Services\Contracts\TrainingCenter\ITrainingCenterService;
use App\Services\Implementations\Service;
class TrainingCenterService extends Service implements ITrainingCenterService {
    public function __construct(ITrainingCenterRepository $repository) {
        parent::__construct($repository);
    }
}