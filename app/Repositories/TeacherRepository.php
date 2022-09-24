<?php

namespace App\Repositories;

use App\Http\Resources\TeacherResource;
use App\Models\Member;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherRepository
{

    /**
     * @var teacher
     */
    protected $teacher;

    /**
     * teacherRepository constructor.
     *
     * @param teacher $teacher
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Get all teacher.
     *
     * @return teacher $teacher
     */
    public function getAll()
    {
        return $this->teacher->get();
    }

    /**
     * Get teacher by id
     *
     * @param $id
     * @return mixed
     */

    public function getById($id)
    {
        return $this->teacher->find($id);
    }
    /**
     * Save teacher
     *
     * @param $data
     * @return teacher
     */

    public function save($data,$school)
    {
        // get school acronym
        $words = explode(" ", $school->name);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }

        // save as user first


        $pass = Str::random(8);
        $user = new User();
        $user->name = $data['first_name'] .' '. $data['last_name'];
        $user->email =  strtolower($data['first_name'] .
            $data['last_name'][1]) . '@.' . $acronym . '.edu.ng';

        $user->password = Hash::make($pass);
        $user->role = 'teacher';

        $user->save();

        // save to member
        Member::create([
            'user_id'=> $user->id,
            'school_id' => $school->id
        ]);

        // save teacher

        $teacher = new $this->teacher;

        $teacher->first_name = $data['first_name'];
        $teacher->last_name = $data['last_name'];
        $teacher->start_year = $data['start_year'];
        $teacher->email = strtolower($teacher->first_name .
             $teacher->last_name[1]) . '@.'.$acronym.'edu.ng';
        $teacher->teacher_category = $data['teacher_category'];
        $teacher->dob = $data['dob'];
        $teacher->gender = $data['gender'];
        $teacher->pssword = $pass;
        $teacher->user_id = $user->id;

        if (!empty($data['passport'])) {
            $image = $data->file('passport');
            $path = public_path('/images/schools/' . $school->slug.'/teachers');
            $image->move($path, $image->getClientOriginalName());
            $teacher->passport = $path . '/' . $image->getClientOriginalName();
        }

        $teacher->save();

        return new TeacherResource($teacher);

    }

    /**
     * Update teacher
     *
     * @param $data
     * @return teacher
     */
    public function update($data, $id)
    {
        $teacher = $this->teacher->find($id);

        $teacher->first_name = $data['first_name'];
        $teacher->last_name = $data['last_name'];
        $teacher->start_year = $data['start_year'];
        $teacher->teacher_category = $data['teacher_category'];
        $teacher->dob = $data['dob'];
        $teacher->gender = $data['gender'];

        return new TeacherResource($teacher);
    }

    /**
     * Delete  teacher
     *
     * @param $data
     * @return teacher
     */
    public function delete($id)
    {
        $teacher = $this->teacher->find($id);
        User::find($teacher->user_id)->delete();
        Member::where('user_id', $teacher->user_id)->first()->delete();
        $teacher->delete();

        return $teacher;
    }
}
