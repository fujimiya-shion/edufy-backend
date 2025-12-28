<?php

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Controllers\Api\ApiController;
use App\Services\Contracts\Order\IOrderService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    use CrudBehaviour;

    protected IOrderService $service;

    public function __construct(IOrderService $service)
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
        return $this->_update($request, service: $this->service, id: $id);
    }

    public function destroy(string $id)
    {
        return $this->_destroy($id, $this->service);
    }

    /**
     * POST /api/v1/orders/checkout
     * - Tận dụng _store và merge user_id vào payload.
     */
    public function checkout(Request $request)
    {
        return $this->_store(
            $request,
            $this->service,
            ['user_id' => (int) $request->user()->id],
            isMerge: true
        );
    }

    /**
     * GET /api/v1/orders/my
     * - Tận dụng _index: tự động xử lý page/per_page/orderBy.
     * - Merge filter từ request + ép user_id = current user.
     */
    public function myOrders(Request $request)
    {
        return $this->_index(
            $request,
            $this->service,
            ['user_id' => (int) $request->user()->id],
            mergeCriteria: true
        );
    }

    /**
     * POST /api/v1/orders/{order}/cancel
     * - Dùng _update: set status = cancelled, đẩy reason vào meta.
     * - Giữ nguyên payload người gọi gửi lên (merge).
     */
    public function cancel(Request $request, string $order)
    {
        $reason = $request->input('reason');

        $custom = [
            'status' => 'cancelled',
            'meta'   => array_filter([
                'cancel_reason' => $reason,
                'cancelled_by'  => (int) $request->user()->id,
            ]),
        ];

        return $this->_update(
            $request,
            $this->service,
            $custom,
            isMerge: true,
            id: (int) $order
        );
    }
}
