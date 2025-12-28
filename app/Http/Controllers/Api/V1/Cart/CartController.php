<?php

namespace App\Http\Controllers\Api\V1\Cart;

use App\Http\Controllers\Api\ApiController;
use App\Services\Contracts\Cart\ICartService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    use CrudBehaviour;

    protected ICartService $service;

    public function __construct(ICartService $service)
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

    /**
     * POST /api/v1/cart/add-item
     */
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer'],
            'quantity'  => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['user_id'] = (int) $request->user()->id;

        return $this->_store($request, $this->service, $validated, isMerge: true);
    }

    /**
     * DELETE /api/v1/cart/remove-item/{id}
     */
    public function removeItem(string $id)
    {
        return $this->_destroy($id, $this->service);
    }

    /**
     * DELETE /api/v1/cart/clear
     */
    public function clear(Request $request)
    {
        $userId = (int) $request->user()->id;
        $result = $this->service->clearByUser($userId);

        return $result
            ? $this->successResponse('Cart cleared successfully')
            : $this->errorResponse('Failed to clear cart', 400);
    }
}
