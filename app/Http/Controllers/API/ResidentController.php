<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\CommonController;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Http\Requests\ResidentRequest;

class ResidentController extends CommonController
{
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
        //
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
