<?php
namespace App\Repositories\Implementations\Ribbon;

use App\Models\RibbonItem;
use App\Repositories\Cache\Behavior\ShouldCache;
use App\Repositories\Contracts\Ribbon\IRibbonItemRepository;
use App\Repositories\Implementations\Repository;
class RibbonItemRepository extends Repository implements IRibbonItemRepository, ShouldCache {
    public function __construct(RibbonItem $model) {

    }
}