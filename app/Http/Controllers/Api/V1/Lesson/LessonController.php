<?php

namespace App\Http\Controllers\Api\V1\Lesson;

use App\Http\Controllers\Api\ApiController;
use App\Services\Contracts\Lesson\ILessonService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;

class LessonController extends ApiController
{
    use CrudBehaviour;
    protected ILessonService $service;
    public function __construct(ILessonService $service) {
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
        return $this->_update($request, id: $id, service: $this->service);
    }

    public function destroy(string $id) {
        return $this->_destroy($id, $this->service);
    }
}
