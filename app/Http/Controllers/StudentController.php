<?php

namespace App\Http\Controllers;


use App\Models\Student;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('students.showall',['students'=>Student::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create():View
    {
    
       $groups=Group::all();
       $courses=Course::all();
       return view('students.add-student',compact('groups','courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { // dd($request['course_id']);
       
          $validator=$request->validate(
            [
                'student_name'=>'required|regex:/^[a-zA-Z]+$/',
                'course_id'=>'required',
                'group_id'=>'required',
                
            ]
          );
          $std=  Student::create($request->all());
            
            $std->courses()->attach($request['course_id']);
           
        
       
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Student::find($id)!=null)
        return Student::find($id)->student_name;
        else 
        return 'No such id';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $groups=Group::all();
        $courses=Course::all();
        $student=Student::find($id);
        $a=[];
foreach( $student->courses()->get() as $course)
        {
          array_push($a,$course->pivot->course_id);
        }
       
        return view('students.update-student',compact('groups','courses','student','a'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $stu=Student::find($id);


        if($stu!=null)
        {
          
         $stu->courses()->sync($request['course_id']);
            }
         
   
    $stu->student_name=$request['student_name'];
    $stu->group_id=$request['group_id'];
    $stu->save();
     
       return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $stu=Student::find($id);
       if($stu!=null)
       {
           if(count($stu->courses()->get()))
           $stu->courses()->detach();
           $stu->delete();
    }
   return redirect()->route('students.index');
 ;
    }
}
