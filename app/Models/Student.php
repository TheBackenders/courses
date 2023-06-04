<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable=['student_name','group_id'];
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('course_id');
    }
    public function group()
    {
return $this->belongsTo(Group::class);

    }
}
