<?php
namespace App\Services\Student;

class StudentLog
{
    private $student;

    public function __construct($student)
    {
        $this->student = $student;

        dd($this->student);
    }
}