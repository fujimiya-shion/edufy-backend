<?php

namespace App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Api\ApiController;
use App\Services\Contracts\Payment\IPaymentService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends ApiController
{
    use CrudBehaviour;

    protected IPaymentService $service;

    public function __construct(IPaymentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->_index($request, $this->service);
    }

    public function store(Request $request)
    {
        return $this->_store($request, $this->service);
    }

    public function show(string $id)
    {
        return $this->_show($id, $this->service);
    }

    public function update(Request $request, string $id)
    {
        return $this->_update($request, id: $id, service: $this->service);
    }

    public function destroy(string $id)
    {
        return $this->_destroy($id, $this->service);
    }

    public function createIntent(Request $request)
    {
        $userId = (int) $request->user()->id;
        return $this->_store($request, $this->service, ['user_id' => $userId], isMerge: true);
    }

    public function confirm(Request $request)
    {
        $id = $request->input('id') ?? $request->input('payment_id');
        if (!$id || !is_numeric($id)) {
            return $this->errorResponse('Payment id is required', 422);
        }
        return $this->_update($request, $this->service, [], isMerge: true, id: (int) $id);
    }

    public function webhook(Request $request)
    {
        Log::info('payment.webhook', ['payload' => $request->all()]);
        return $this->success('Webhook received');
    }
}
