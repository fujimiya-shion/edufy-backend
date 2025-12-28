<?php

namespace App\Http\Controllers\Api\V1\Ribbon;

use App\Http\Controllers\Api\ApiController;
use App\Services\Contracts\Ribbon\IRibbonService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;

class RibbonController extends ApiController
{
    use CrudBehaviour;

    protected IRibbonService $service;

    public function __construct(IRibbonService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->_index(
            $request,
            $this->service,
            customOptions: [
                'with' => ['activeItems.course'],
                'orderBy' => ['order' => 'asc', 'id' => 'asc'],
            ]
        );
    }

    public function store(Request $request)
    {
        return $this->_store($request, $this->service);
    }

    public function show(string $id)
    {
        return $this->_show(
            $id,
            $this->service,
            customOptions: [
                'with' => ['activeItems.course'],
            ]
        );
    }

    public function update(Request $request, string $id)
    {
        return $this->_update($request, $this->service, id: $id);
    }

    public function destroy(string $id)
    {
        return $this->_destroy($id, $this->service);
    }
}
