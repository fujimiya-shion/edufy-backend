<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Contracts\Searchable;
use App\Services\Contracts\Teacher\ITeacherService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;

class TeacherController extends ApiController implements Searchable
{
    use CrudBehaviour;

    protected ITeacherService $service;

    public function __construct(ITeacherService $service) {
        $this->service = $service;
    }
    
    public function index(Request $request) {
        return $this->_index($request, $this->service);
    }

    public function store(Request $request) {
        return $this->_store($request, $this->service);
    }

    public function show(string $id) {
        return $this->_show($id, $this->service);
    }

    public function update(Request $request, string $id) {
        return $this->_update($request, $this->service, id: $id);
    }

    public function destroy(string $id) {
        return $this->_destroy($id, $this->service);
    }

    public function search(Request $request) {
        $request->merge(['column' => 'full_name']);
        return $this->_search($request, $this->service);
    }
}
