<?php

namespace App\Services;

use App\Models\teacher;
use App\Repositories\TeacherRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TeacherService
{
    /**
     * @var $teacherRepository
     */
    protected $teacherRepository;

    /**
     * PostService constructor.
     *
     * @param teacherRepository $teacherRepository
     */
    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Delete post by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->teacherRepository->delete($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;
    }

    /**
     * Get all post.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->teacherRepository->getAll();
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->teacherRepository->getById($id);
    }

    /**
     * Update post data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updatePost($data, $id)
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'start_year' => 'nullable|date_format:Y-m-d',
            'passport' => 'nullable||image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'dob' => 'nullable|date_format:Y-m-d',
            'gender' => 'required_in:m,f|string'

        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $post = $this->teacherRepository->update($data, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;
    }

    /**
     * Validate post data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($data,$school)
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'  => 'required|string|unique:users,email',
            'start_year' => 'nullable|date_format:Y-m-d',
            'passport' => 'nullable||image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'dob' => 'nullable|date_format:Y-m-d',
            'gender' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->teacherRepository->save($data,$school);

        return $result;
    }
}
