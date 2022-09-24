<?php

namespace App\Http\Controllers\Management\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolCategory;
use Illuminate\Http\Request;

class SchoolCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $schoolCategory = SchoolCategory::paginate(50)->
            getData(true);

        return response()->json($schoolCategory);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$slug)
    {
        //
        $school = School::get_school($slug);
        $cat = new SchoolCategory();

        $cat->name = $request->name;
        $cat->description = $request->description;
        $cat->school_id = $school->id;

        $cat->save();

        return response()->json($cat);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Management\School\SchoolCategory  $schoolCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        $schoolCategory = SchoolCategory::find($request->id);
        $schoolCategory->name = $request->name;
        $schoolCategory->description = $request->description;
        $schoolCategory->update();

        return response()->json($schoolCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Management\School\SchoolCategory  $schoolCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolCategory $schoolCategory)
    {

        $schoolCategory->delete();

        return response()->json($schoolCategory);
    }
}
