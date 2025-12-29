<?php
namespace App\Repositories\Implementations\Ribbon;

use App\Models\Ribbon;
use App\Repositories\Cache\Behavior\ShouldCache;
use App\Repositories\Contracts\Ribbon\IRibbonRepository;
use App\Repositories\Implementations\Repository;
class RibbonRepository extends Repository implements IRibbonRepository, ShouldCache {
    public function __construct(Ribbon $model) {
        parent::__construct($model);
    }
}