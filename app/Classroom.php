<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $primaryKey = 'classroom_id';
    protected $table = 'classrooms';
    protected $fillable = [
        'name',
        'teacher_id',
    ];

    public $timestamps = true;

    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'teacher_id', 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany('App\Student', 'classroom_id', 'classroom_id');
    }
}