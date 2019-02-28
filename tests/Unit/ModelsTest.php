<?php

namespace Tests\Unit;

use App\Classroom;
use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Models test.
     *
     * @return void
     */
    public function testUserModel()
    {
        $user = User::create([
            "name" => "test",
            "email" => "test@mail.com",
            "password" => 'test'
        ]);
        $this->assertDatabaseHas('users', ["name" => "test",
                "email" => "test@mail.com",
                "password" => 'test']
        );

        $userUpdate = User::find($user->id);
        $userUpdate->name = 'test2';
        $userUpdate->email = 'test2@mail.com';
        $userUpdate->password = 'test2';
        $userUpdate->update();
        $this->assertDatabaseHas('users', ["name" => "test2",
                "email" => "test2@mail.com",
                "password" => 'test2']
        );

        User::destroy($user->id);
        $this->assertDatabaseMissing('users', ["name" => "test2",
                "email" => "test2@mail.com",
                "password" => 'test2']
        );
    }

    public function testTeacherModel()
    {
        $teacher = Teacher::create(["name" => "test"]);
        $this->assertDatabaseHas('teachers', ["name" => "test"]);

        $teacherUpdate = Teacher::find($teacher->teacher_id);
        $teacherUpdate->name = 'test2';
        $teacherUpdate->update();
        $this->assertDatabaseHas('teachers', ["name" => "test2"]);

        Teacher::destroy($teacher->teacher_id);
        $this->assertDatabaseMissing('teachers', ["name" => "test2"]);
    }

    public function testClassroomModel()
    {
        $teacher = Teacher::create(["name" => "Teacher Name"]);

        $classroom = new Classroom();
        $classroom->name = 'test';
        $classroom->teacher_id = $teacher->teacher_id;
        $classroom->save();
        $this->assertDatabaseHas('classrooms', ["name" => "test", 'teacher_id' => $teacher->teacher_id]);

        $classroomUpdate = Classroom::find($classroom->classroom_id);
        $classroomUpdate->name = 'test2';
        $classroomUpdate->teacher_id = $teacher->teacher_id;
        $classroomUpdate->update();
        $this->assertDatabaseHas('classrooms', ["name" => "test2", 'teacher_id' => $teacher->teacher_id]);

        Classroom::destroy($classroom->classroom_id);
        $this->assertDatabaseMissing('classrooms', ["name" => "test2", 'teacher_id' => $teacher->teacher_id]);
    }

    public function testStudentModel()
    {
        $classroom = Classroom::create(["name" => "class name"]);

        $student = Student::create(["name" => "test", 'classroom_id' => $classroom->classroom_id]);
        $this->assertDatabaseHas('students', ["name" => "test", 'classroom_id' => $classroom->classroom_id]);

        $studentUpdate = Student::find($student->student_id);
        $studentUpdate->name = 'test2';
        $studentUpdate->classroom_id = $classroom->classroom_id;
        $studentUpdate->update();
        $this->assertDatabaseHas('students', ["name" => "test2", 'classroom_id' => $classroom->classroom_id]);

        Student::destroy($student->student_id);
        $this->assertDatabaseMissing('students', ["name" => "test2", 'classroom_id' => $classroom->classroom_id]);
    }
}
