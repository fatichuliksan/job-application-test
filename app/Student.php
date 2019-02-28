<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';
    protected $table = 'students';
    protected $fillable = [
        'name',
        'classroom_id',
    ];

    public $timestamps = true;

    public function classroom()
    {
        return $this->belongsTo('App\Classroom', 'classroom_id', 'classroom_id');
    }
}