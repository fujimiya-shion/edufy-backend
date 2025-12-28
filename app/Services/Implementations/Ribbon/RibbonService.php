<?php
namespace App\Services\Implementations\Ribbon;

use App\Repositories\Contracts\Ribbon\IRibbonRepository;
use App\Services\Contracts\Ribbon\IRibbonService;
use App\Services\Implementations\Service;
class RibbonService extends Service implements IRibbonService {
    public function __construct(IRibbonRepository $repository) {
        parent::__construct($repository);
    }
}