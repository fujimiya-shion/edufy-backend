<?php

namespace App\Http\Controllers\Api\V1\Course;

use App\Http\Controllers\Api\ApiController;
use App\Services\Contracts\Course\ICourseService;
use App\Traits\CrudBehaviour;
use Illuminate\Http\Request;

class CourseController extends ApiController
{
    use CrudBehaviour;
    protected ICourseService $service;
    public function __construct(ICourseService $service) {
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
}
