<?php

namespace App\Repositories;

use App\Http\Resources\SchoolClassResource;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class SchoolClassRepository
{

    /**
     * @var schoolClass
     */
    protected $schoolClass;

    /**
     * schoolClassRepository constructor.
     *
     * @param schoolClass $schoolClass
     */
    public function __construct(SchoolClass $schoolClass)
    {
        $this->schoolClass = $schoolClass;
    }

    /**
     * Get all schoolClass.
     *
     * @return schoolClass $schoolClass
     */
    public function getAll()
    {
        return $this->schoolClass->get();
    }

    /**
     * Get schoolClass by id
     *
     * @param $id
     * @return mixed
     */

    public function getById($id)
    {
        return $this->schoolClass->find($id);
    }
    /**
     * Save schoolClass
     *
     * @param $data
     * @return schoolClass
     */

    public function save($data, $school)
    {
        $schoolClass = new $this->schoolClass;

        $schoolClass->name = $data['name'];
        $schoolClass->description = $data['description'];
        $schoolClass->school_id = $school->id;

        $schoolClass->save();

        return $schoolClass;

    }

    /**
     * Update schoolClass
     *
     * @param $data
     * @return schoolClass
     */
    public function update($data, $id)
    {
        $schoolClass = $this->schoolClass->find($id);

        $schoolClass->name = $data['name'];
        $schoolClass->description = $data['description'];

        $schoolClass->update();

        return $schoolClass;
    }

    /**
     * Delete  schoolClass
     *
     * @param $data
     * @return schoolClass
     */
    public function delete($id)
    {
        $schoolClass = $this->schoolClass->find($id);
        $schoolClass->delete();

        return $schoolClass;
    }
}
