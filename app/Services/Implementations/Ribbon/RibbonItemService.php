<?php
namespace App\Services\Implementations\Ribbon;

use App\Repositories\Contracts\Ribbon\IRibbonItemRepository;
use App\Services\Contracts\Ribbon\IRibbonItemService;
use App\Services\Implementations\Service;
class RibbonItemService extends Service implements IRibbonItemService {
    public function __construct(IRibbonItemRepository $repository) {
        parent::__construct($repository);
    }
}