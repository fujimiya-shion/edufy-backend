<?php
namespace App\Traits;

use App\Services\Contracts\IService;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait CrudBehaviour {
    public const PAGINATION_PERPAGE = 15;

    public function getOptions(Request $request) {
        $perpage = $request->input('per_page', self::PAGINATION_PERPAGE);
        $page = $request->input('page', 1);

        $perpage = is_numeric($perpage) ? intval(round($perpage)) : self::PAGINATION_PERPAGE;
        $page = is_numeric($page) ? intval(round($page)) : 1;

        $options = [
            'perpage' => $perpage,
            'page' => $page,
        ];

        if (isset($request->order_column)  && isset($request->order_direction)) {
            $options['orderBy'] = [
                $request->order_column => $request->order_direction
            ];
        }

        return $options;
    }
    public function _index(Request $request, IService $service, $customCriteria = [], $mergeCriteria = true, $customOptions = []) {
        $options = $this->getOptions($request);
        $options = array_merge($options, $customOptions);
        $perpage = $options['perpage'];
        $page = $options['page'];
        try {
            $criteria = [];
            if($mergeCriteria) {
                $criteria = array_merge($request->except(['per_page', 'page', 'order_column', 'order_direction']), $customCriteria);
            } else {
                $criteria = $customCriteria && count($customCriteria) > 0 ? $customCriteria : $request->except(['per_page', 'page']);
            }
            $total = $service->count($criteria);
            $all = $criteria ? $service->getBy($criteria, $options) : $service->getAll($options);
            $pageCount = $perpage > 0 ? ceil($total / $perpage) > 0 ? ceil($total / $perpage) : 1 : 1;
            return $this->successResponse(__('Dữ liệu đã được lấy thành công'), $all, 200, [
                'total' => $total,
                'page_count' => $pageCount,
                'per_page' => $perpage,
                'page' => $page
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse(__('Có lỗi xảy ra'), 500);
        }
    }

    public function _show(Request | string | int $request, IService $service, $customOptions = []) {
        try {

            $id = (is_numeric($request) || is_string($request))
                ? (int) $request
                : (int) $request->route('id');

            $item = $service->getById($id);

            if ($item) {
                return $this->successResponse(__('Dữ liệu đã được lấy thành công'), $item);
            }
            return $this->errorResponse(__('Không tìm thấy dữ liệu'), 404);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse(__('Có lỗi xảy ra'), 500);
        }
    }

    public function _store(Request | FormRequest $request, IService $service, array $customData = [], bool $isMerge = false) {
        try {
            $created = null;
            if($isMerge)
                $created = $service->create(array_merge($request->all(), $customData));
            else {
                $created = empty($customData) ? $service->create($request->all()) : $service->create($customData);
            }
            return $created
                ? $this->successResponse(__('Dữ liệu đã được tạo thành công'), $created)
                : $this->errorResponse(__('Dữ liệu chưa được tạo'), 400);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->errorResponse(__('Có lỗi xảy ra'), 500);
        }
    }

    public function _update(Request | FormRequest $request, IService $service, array $customData = [], bool $isMerge = false, int|string|null $id = null) {
        try {
            $id = (is_numeric($id) || is_string($id))
                ? (int) $id
                : (int) $request->route('id');
            $updated = null;
            if($isMerge)
                $updated = $service->update($id, array_merge($request->all(), $customData));
            else {
                $updated = empty($customData) ? $service->update($id, $request->all()) : $service->update($id, $customData);
            }

            return $updated
                ? $this->successResponse('Data updated successfully', $updated)
                : $this->errorResponse('Data not updated', 400);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->errorResponse('Something went wrong', 500);
        }
    }

    public function _destroy(Request | int | string $request, IService $service) {
        try {
            $id = (is_numeric($request) || is_string($request))
                ? (int) $request
                : (int) $request->route('id');
            $deleted = $service->delete($id);
            return $deleted
                ? $this->successResponse('Data deleted successfully')
                : $this->errorResponse('Data not deleted', 400);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('Something went wrong', 500);
        }
    }

    public function _search(Request $request, IService $service){
        $keyword = $request->get('keyword') ?? '';
        $column = $request->get('column') ?? 'name';
        $selectedColumns = explode(',', $request->get('selectedColumns'));
        if(is_bool($selectedColumns))
            $selectedColumns = ["*"];

        $options = $this->getOptions($request);
        $records = $service->autoComplete($keyword, $column, $selectedColumns, $options);
        $total = $service->autoCompleteCount($keyword, $column);
        $meta = $this->makePagination($request, $total);
        return $this->successResponse("Tìm kiếm thành công", $records, 200, $meta);
    }

    protected function successResponse(string $message, $data = null, $status = 200, $customData = []) {
        return response()->json(array_merge($customData, [
            'status' => true,
            'status_code' => $status,
            'message' => $message,
            'data' => $data,
        ]), 200);
    }

    protected function errorResponse(string $message, int $status = 400) {
        return response()->json([
            'status' => false,
            'status_code' => $status,
            'message' => $message,
        ], $status);
    }

    protected function makePagination(Request $request, $total) {
        $options = $this->getOptions($request);
        $perpage = $options['perpage'];
        $page = $options['page'];
        $pageCount = $perpage > 0 ? ceil($total / $perpage) : 1;
        return [
            'total' => $total,
            'page_count' => $pageCount,
            'per_page' => $perpage,
            'page' => $page
        ];
    }
}
