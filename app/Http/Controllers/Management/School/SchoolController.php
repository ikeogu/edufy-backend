<?php

namespace App\Http\Controllers\Management\School;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolResource;
use App\Models\Member;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\SchoolCategory;
use App\Services\SchoolService;
use Exception;

class SchoolController extends Controller
{

    //
    /**
     * @var schoolService
     */
    protected $schoolService;

    /**
     * PostController Constructor
     *
     * @param SchoolService $schoolService
     *
     */
    public function __construct(SchoolService $schoolService)
    {
        $this->schoolService = $schoolService;
    }


    public  function createSchool($data)
    {
        try {
            $result = $this->schoolService->savePostData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->schoolService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->schoolService->savePostData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->schoolService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    /**
     * Update post.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'name',
            'location',
            'email',
            'phone',
            'website',
            'logo',
            'description'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->schoolService->updatePost($data, $id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->schoolService->deleteById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    // setup school

    public function setup_school(Request $request){


        $data = $request->only([
            'name',
            'location',
            'email',
            'phone',
            'website',
            'logo',
            'description',
        ]);

        $school = $this->createSchool($data);

        $user = auth()->user();

        $member = SchoolController::add_user_to_school($user, $school, 'school_admin', 1);


        foreach($request->school_category as $category){

            SchoolCategory::create([
                'name' => $category->name,
                'description' => $category->description
            ]);
        }


        return response()->json([
            'status' => 200,
            'message' => 'School setup successfully',
            'data' => new SchoolResource($school)
        ]);
    }

    public static function add_user_to_school($user, $school, $role, $status)
    {
        $role = is_null($role) ? 'student' : $role;

        if($role == 'school_admin'){
            if(!$user->admin_of_school($school)){

               $admin =  SchoolAdmin::create([
                    'user_id' => $user->id,
                    'school_id' => $school->id
                ]);

                return Member::create([
                    'user_id' => $user->id,
                    'school_id' => $school->id,
                    'role' => $role,
                    'status' => $status,
                ]);
            }
        }

        if (!$user->belongs_to_school($school)) {

            return Member::create([
                'user_id' => $user->id,
                'school_id' => $school->id,
                'role' => $role,
                'status' => $status,
            ]);
        }
    }

    public function get_school($slug)
    {
        $school = School::get_school($slug);
        return response()->json([
            'status' => 200,
            'message' => 'School gotten successfully',
            'data' => new SchoolResource($school)
        ]);
    }
}
