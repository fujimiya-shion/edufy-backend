<?php

namespace App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Api\ApiController;
use App\Services\Contracts\Payment\IPaymentLogService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;

class PaymentLogController extends ApiController
{
    use CrudBehaviour;

    protected IPaymentLogService $service;

    public function __construct(IPaymentLogService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->_index($request, $this->service);
    }

    public function show(string $id)
    {
        return $this->_show($id, $this->service);
    }
}
