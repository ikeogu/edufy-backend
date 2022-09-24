<?php

namespace App\Repositories;

use App\Http\Resources\SchoolResource;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolRepository
{

    /**
     * @var school
     */
    protected $school;

    /**
     * schoolRepository constructor.
     *
     * @param school $school
     */
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * Get all school.
     *
     * @return school $school
     */
    public function getAll()
    {
        return $this->school->get();
    }

    /**
     * Get school by id
     *
     * @param $id
     * @return mixed
     */

    public function getById($id)
    {
        return $this->school->find($id);
    }
    /**
     * Save school
     *
     * @param $data
     * @return school
     */

    public function save($data)
    {
        $school = new $this->school;

        $school->name = $data['name'];
        $school->location = $data['location'];
        $school->email = $data['email'];
        $school->phone = $data['phone'];
        $school->website = $data['website'];
        $school->description = $data['description'];
        $school->status = 1;
        $school->slug = School::makeSlugFromName($data['name']);

        if (!empty($data['logo'])) {
            $image = $data->file('logo');
            $path = public_path('/images/schools/'.$school->slug);
            $image->move($path, $image->getClientOriginalName());
            $school->logo = $path . '/' . $image->getClientOriginalName();
        }

        $school->save();

        return new SchoolResource($school);
    }

    /**
     * Update school
     *
     * @param $data
     * @return school
     */
    public function update($data, $id)
    {
        $school = $this->school->find($id);

        if(!empty($data['logo'])){
            $image = $data->file('logo');
            $path = public_path('/images/schools/'.$school->slug);
            $image->move($path, $image->getClientOriginalName());
            $school->logo = $path . '/' . $image->getClientOriginalName();
        }
        $school->name = $data['name'] ?? $school->name;
        $school->location = $data['location'] ?? $school->location;
        $school->email = $data['email'] ?? $school->email;
        $school->phone = $data['phone'] ?? $school->phone;
        $school->website = $data['website'] ?? $school->website;
        $school->description = $data['description'] ?? $school->description;
        $school->slug = School::makeSlugFromName($data['name']) ?? $school->slug;
        $school->update();

        return $school;
    }

    /**
     * Delete  school
     *
     * @param $data
     * @return school
     */
    public function delete($id)
    {
        $school = $this->school->find($id);
        $school->delete();

        return $school;
    }


}
