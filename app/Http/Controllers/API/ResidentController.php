<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\CommonController;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Http\Requests\ResidentRequest;
use App\Services\ResidentService;
use App\Helpers\Helper;

class ResidentController extends CommonController
{
    use ApiResponse;

    /**
     * @var \App\Services\ResidentService
     */
    private $resident_service;

    /**
     * @var \App\Helpers\Helper
     */
    private $helper;

    public $model = "Resident";

    public function getModel()
    {
        return Resident::class;
    }

    public function __construct(ResidentService $resident_service, Helper $helper)
    {
        $this->resident_service = $resident_service;
        $this->helper = $helper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data_query = Resident::list_show_query();

        if ($request->filled('search_string')) {
            $data_query->where('resident_type', 'like', '%' . $request->search_string . '%');
        }
        $fields = ['id'];
        return $this->commonpagination($request, $data_query, $fields);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ResidentRequest $request)
    {
        try {
            $data = $request->all();
            $save_resident = $this->resident_service->save_resident($data, $request);
            return $save_resident;
        } catch (\Exception $ex) {
            return $this->customResponse(-1, null, $ex->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ResidentRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
