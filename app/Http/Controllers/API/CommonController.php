<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CommonService;
use App\Traits\ApiResponse;

class CommonController extends Controller
{

  use ApiResponse;

  public $common_service;
  public function __construct(CommonService $common_service)
  {
    $this->common_service = $common_service;
  }

  public function cities(Request $request, $state_id = 0)
  {
    $data = $request->all();
    $data['state_id'] = $state_id ?? null;

    $cities = $this->common_service->getCities($data);
    if (isset($cities)) {
      $response['status'] = true;
      $response['message'] = "Cities found!";
      $response['data'] = $cities;

      return response()->json($response, 200);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request, $id)
  {
    $data_query = $this->getModel()->list_show_query();
    if (isset($id) && !empty($id)) {
      $data_query = $data_query->where('id', $id);
    }
    $data_query = $data_query->first()->toArray();
    if (isset($data_query) && !empty($data_query)) {
      return $this->customResponse(1, trans("translate.record_found", ["model" => $this->model]), $data_query);
    } else {
      return $this->customResponse(8, trans("translate.record_not_found"));
    }
  }

  public function destroy(string $id)
  {
    $model = $this->getModel();

    $delete_record = $model::where(['id' => $id])->first();
    if ($delete_record) {
      $delete_record->Delete();
      return $this->customResponse(1, trans("translate.record_deleted", ["model" => $this->model]));
    } else {
      return $this->customResponse(8, trans("translate.no_record_found"));
    }
  }

  function commonpagination($request, $data_query, $fields = [], $params = [])
  {

    $sort_by = 'id';
    if (isset($request->sort_by) && !empty($request->sort_by)) {
      $sort_by = $request->sort_by;
    }
    if (!in_array($sort_by, $fields)) {
      return $this->customResponse(2, trans("translate.invalid_requrst_parameterrs", implode(', ', $fields)));
    }

    $sort_order = isset($request->sort_order) && in_array($request->sort_order, ['asc', 'desc']) ? $request->sort_order : 'desc';

    $per_page = $request->per_page ?: 200;
    $page = $request->page ? $request->page : 0;
    $data_query->orderBy($sort_by, $sort_order);

    //        dd($page, $per_page);

    $response = [];
    // Otherwise, proceed with regular pagination
    if (isset($page) && !empty($page) && $page > 0) {
      $data_query = $data_query->paginate($per_page, ['*'], 'page', $page);

      $data = collect($data_query->items());
      $pagination = array(
        'total' => $data_query->total(),
        'per_page' => $data_query->perPage(),
        'current_page' => $data_query->currentPage(),
        'last_page' => $data_query->lastPage(),
        'next_page_url' => $data_query->nextPageUrl(),
        'prev_page_url' => $data_query->previousPageUrl(),
        'from' => $data_query->firstItem(),
        'to' => $data_query->lastItem()
      );
    } else {
      $data = $data_query->get();
      $pagination = null;
    }
    if ($data->isEmpty()) {
      return $this->customResponse(8, trans("translate.no_record_found"));
    } else {
      return $this->customResponse(1, trans("translate.record_found", ["model" => $this->model]), $data, $pagination);
    }
  }
}
