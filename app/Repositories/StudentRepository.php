<?php

namespace App\Repositories;

use App\Http\Resources\StudentResource;
use App\Models\Member;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentRepository
{

    /**
     * @var student
     */
    protected $student;

    /**
     * studentRepository constructor.
     *
     * @param student $student
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * Get all student.
     *
     * @return student $student
     */
    public function getAll()
    {
        return $this->student->get();
    }

    /**
     * Get student by id
     *
     * @param $id
     * @return mixed
     */

    public function getById($id)
    {
        return $this->student->find($id);
    }
    /**
     * Save student
     *
     * @param $data
     * @return student
     */

    public function save($data,$school)
    {
        $student = new $this->student;

        // get school acronym
        $words = explode(" ", $school->name);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }

        // save as user first


        $pass = Str::random(8);
        $user = new User();
        $user->name = $data['first_name'] . ' ' . $data['last_name'];
        $user->email =  strtolower($data['first_name'] .
        $data['last_name'][1]) . '@.' . $acronym . '.edu.ng';

        $user->password = Hash::make($pass);
        $user->role = 'student';

        $user->save();

        // save to member
        Member::create([
            'user_id' => $user->id,
            'school_id' => $school->id
        ]);

        // save student

        $student = new $this->student;

        $student->first_name = $data['first_name'];
        $student->last_name = $data['last_name'];
        $student->middle_name = $data['middle_name'];
        $student->gender = $data['gender'];
        $student->p_email = $data['p_email'];
        $student->reg_no = $data['reg_no'];
        $student->dob = $data['dob'];
        $student->contact = $data['contact'];

        $student->email = strtolower($student->first_name .
            $student->last_name[1]) . '@.' . $acronym . 'edu.ng';

        $student->pssword = $pass;
        $student->user_id = $user->id;

        if (!empty($data['passport'])) {
            $image = $data->file('passport');
            $path = public_path('/images/schools/' . $school->slug . '/student');
            $image->move($path, $image->getClientOriginalName());
            $student->passport = $path . '/' . $image->getClientOriginalName();
        }

        $student->save();

        return  new StudentResource($student);

    }

    /**
     * Update student
     *
     * @param $data
     * @return student
     */
    public function update($data, $id)
    {
        $student = $this->student->find($id);

        $student->first_name = $data['first_name'];
        $student->last_name = $data['last_name'];
        $student->middle_name = $data['middle_name'];
        $student->gender = $data['gender'];
        $student->p_email = $data['p_email'];
        $student->reg_no = $data['reg_no'];
        $student->dob = $data['dob'];
        $student->contact = $data['contact'];

        $student->save();

        $user = User::find($student->user_id);
        $user->name = $student->first_name .' '. $student->last_name;
        $user->save();

        return new StudentResource($student);
    }

    /**
     * Delete  student
     *
     * @param $data
     * @return student
     */
    public function delete($id)
    {
        $student = $this->student->find($id);
        User::find($student->user_id)->delete();
        Member::where('user_id',$student->user_id)->first()->delete();
        $student->delete();

        return $student;
    }
}
