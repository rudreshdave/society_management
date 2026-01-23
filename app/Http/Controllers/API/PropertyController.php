<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\CommonController;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends CommonController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data_query = Property::list_show_query();

        if ($request->filled('search_string')) {
            $data_query->where('wing_no', 'like', '%' . $request->search_string . '%');
        }
        $fields = ['id', 'wing_no'];
        return $this->commonpagination($request, $data_query, $fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
