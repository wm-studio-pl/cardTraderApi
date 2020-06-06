<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Subcategory;
use App\Http\Resources\Subcategory as SubcategoryResource;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = Subcategory::paginate(10);
        return SubcategoryResource::collection($subcategories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subcategory = $request->isMethod('put')
            ? Subcategory::findOrFail($request->subcategory_id)
            : new Subcategory();
        $subcategory->id = $request->input('subcategory_id');
        $subcategory->name = $request->input('name');
        $subcategory->order = $request->input('order');
        $subcategory->style = $request->input('style');

        if ($subcategory->save()) {
                return new SubcategoryResource($subcategory);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        return new SubcategoryResource($subcategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        if ($subcategory->delete()) {
            return new SubcategoryResource($subcategory);
        }
    }
}
