<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    use ApiResponse;

    // /**
    //  * Lấy danh sách các mục đã bị xóa mềm (trashed items) và trả về dưới dạng JSON.
    //  * * @param string $modelClass Tên lớp của Model (vd: App\Models\Category::class)
    //  * @return \Illuminate\Http\JsonResponse
    //  */pw
    // public function tras(string $modelClass)
    // {
    //     if (!in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses($modelClass))) {
    //         return $this->jsonResponse([
    //             'message' => "Error: Model does not use Soft Deletes.",
    //             'success' => false
    //         ], 400);
    //     }
    //     $trashedItems = $modelClass::onlyTrashed()
    //                                 ->orderBy('id', 'ASC')
    //                                 ->get();
    //     return $this->jsonResponse([
    //         'success' => true,
    //         'data' => $trashedItems
    //     ]);
    // }




    // /**
    //  * Thực hiện xóa mềm (Soft Delete) một đối tượng của Model bất kỳ và trả về JSON.
    //  *
    //  * @param Request $request Yêu cầu HTTP chứa ID của mục cần xóa.
    //  * @param string $modelClass Tên lớp của Model (ví dụ: 'App\Models\Category').
    //  * @return JsonResponse
    //  */
    // public function deleteGeneric(Request $request, string $modelClass): JsonResponse
    // {
    //     try {
    //         // 1. Tìm đối tượng bằng ID.
    //         // Sử dụng $modelClass::findOrFail để gọi hàm tĩnh trên lớp Model được truyền vào.
    //         $item = $modelClass::findOrFail($request->id);

    //         // 2. Cập nhật trường 'deleted' thành true (hoặc 1) để thực hiện xóa mềm
    //         // Lưu ý: Nếu bạn đang dùng SoftDeletes chuẩn của Laravel, hãy dùng $item->delete(); thay vì $item->deleted = true;
    //         $item->deleted = true;

    //         // 3. Lưu thay đổi vào cơ sở dữ liệu.
    //         $item->save();

    //         // 4. Trả về phản hồi JSON thành công
    //         return $this->jsonResponse([
    //             'success' => true,
    //             'message' => class_basename($modelClass) . ' đã được chuyển vào thùng rác thành công.',
    //         ], 200);

    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         // Xử lý trường hợp không tìm thấy ID
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Không tìm thấy mục cần xóa.',
    //         ], 404);

    //     } catch (\Exception $e) {
    //         // Xử lý các lỗi khác
    //         return $this->jsonResponse([
    //             'success' => false,
    //             'message' => 'Có lỗi xảy ra trong quá trình xóa: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    //------------------------------------------------ Đang dùng ------------------------------------------------//
    /**
     * Trả về dữ liệu phân trang với filter search $q và with relations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @param array $searchColumns // cột search mặc định ['ten', 'mota']
     * @param array $relations     // quan hệ eager load
     */
    public function paginateAndFilter($query, Request $request, array $searchColumns = ['ten'], array $relations = [])
    {
        $perPage     = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $q           = $request->get('q', null);
        // Load relations
        if (!empty($relations)) {
            $query->with($relations);
        }
        // Filter search $q
        if ($q) {
            $query->where(function($subQuery) use ($q, $searchColumns) {
                foreach ($searchColumns as $col) {
                    $subQuery->orWhere($col, 'like', "%{$q}%");
                }
            });
        }
        $items = $query->latest('updated_at')
                       ->paginate($perPage, ['*'], 'page', $currentPage);

        // Check nếu page vượt quá
        if ($currentPage > $items->lastPage() && $currentPage > 1) {
            return [
                'status'  => false,
                'message' => 'Trang không tồn tại. Trang cuối cùng là ' . $items->lastPage(),
                'data'    => $items,
                'meta'    => [
                    'current_page' => $currentPage,
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $perPage,
                    'total'        => $items->total(),
                ],
                'http_code' => 404
            ];
        }
        return [
            'status'  => true,
            'data'    => $items,
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'next_page_url'=> $items->nextPageUrl(),
                'prev_page_url'=> $items->previousPageUrl(),
            ],
            'http_code' => 200
        ];
    }

}


// public function trash(Model $model)
// {
//     // $model::onlyTrashed() sẽ tự động thêm điều kiện WHERE "deleted_at" IS NOT NULL
//     // Đây là cách viết chuẩn và an toàn nhất trong Laravel cho Soft Deletes.
//     $trashedItems = $model::onlyTrashed()
//                             ->orderBy('id', 'ASC')
//                             ->get();

//     // Tên biến $categories không còn chính xác khi dùng tổng quát, nên đổi thành $trashedItems
//     return view('admin.trash', compact('trashedItems'));
//     // Giả định view tổng quát là 'admin.trash', hoặc bạn có thể tạo tên view động
// }
// public function delete(Request $request)
// {
//     // Tìm đối tượng Category bằng ID nhận được từ request.
//     // Nếu không tìm thấy, Laravel sẽ tự động ném ra lỗi 404.
//     $category = Category::findOrFail($request->id);

//     // Gán giá trị thời gian hiện tại cho trường 'deleted_at' để đánh dấu danh mục đã bị xóa mềm.
//     $category->deleted_at = now();// Carbon::now()->addHour(7);

//     // Lưu thay đổi vào cơ sở dữ liệu.
//     $category->save();

//     // Chuyển hướng người dùng trở lại trang danh sách Category trong khu vực admin.
//     return redirect('admin/category');
// }
// public function restore(Request $request)
// {
//     $category = Category::findOrFail($request->id);

//     // Đặt trường 'deleted_at' về NULL (Đây là cách chuẩn của Soft Deletes Laravel)
//     $category->deleted_at = null;

//     $category->save();

//     return redirect('admin/category');
// }
