<?php
namespace App\Repositories\Implementations\Ribbon;

use App\Models\Ribbon;
use App\Repositories\Cache\Behavior\ShouldCache;
use App\Repositories\Contracts\Ribbon\IRibbonItemRepository;
use App\Repositories\Implementations\Repository;
class RibbonRepository extends Repository implements IRibbonItemRepository, ShouldCache {
    public function __construct(Ribbon $model) {
        parent::__construct($model);
    }
}