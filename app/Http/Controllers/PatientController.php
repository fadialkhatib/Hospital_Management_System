<?php

namespace App\Http\Controllers;

use App\Models\ActiveToken;
use App\Models\Department;
use App\Models\Patient;
use App\Models\Patient_file;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function add_patient(Request $request)
    {
        try{
        $validate = $request->validate([
            'full_name'=>'required',
            'address'  =>'required',
            'date_of_birth'=>'required|date',
            'mom_name'=>'required',
            'chain'=>'required|integer',
            'gender'=>'required',
            'case_description'=>'required',
            'treatment_required'=>'required',
        ]);
        $token=json_decode(base64_decode($request->header('token')));
        $patient = Patient::create([
            'full_name'=>$validate['full_name'],
            'address'=>$validate['address'],
            'date_of_birth'=>$validate['date_of_birth'],
            'mom_name'=>$validate['mom_name'],
            'chain'=>$validate['chain'],
            'gender'=>$validate['gender'],
            'case_description'=>$validate['case_description'],
            'treatment_required'=>$validate['treatment_required']
        ]);

        if($validate['treatment_required'] == 'emergency treatment')
        {
            $attach = Patient_file::create([
                'patient_id'=>$patient->id,
                'department_id'=>1,
                'test_result'=>[],
                'X_ray_result'=>[],
            ]);
        }
    }catch(\Exception $e)
    {
        return response()->json(['message'=>$e->getMessage()],401);

    }
        return response()->json(['message'=>'patient info added successfully!'],200);

    }


    public function transfer_patient_dep(Request $request)
    {
        $token = $request->header('token');
        $depof_employee = ActiveToken::where('token',$token)->value('department_id');
        $patient = Patient::where('id',$request->patient_id)->first();
        $check = Patient_file::where('department_id',$depof_employee)
        ->where('patient_id',$patient->id)->first();
        if(!$check)
        {
            return response()->json(['message'=>'this patient is not in this department so you cannot tranfer him !'],401);
        }
        $trnsfer = Patient_file::where('patient_id',$request->patient_id)->where('department_id',$depof_employee)->update([
            'department_id'=>$request->tr_department
        ]);
        return response()->json(['message'=>'patient transfered succesfully']);
    }


    public function patient_file(Request $request)
    {
        $patient_info = Patient::where('id',$request->patient_id)->first();
       
        if(!$patient_info)
        {
            return response()->json(['message'=>'this patient is not exist in the system']);
        }
        $department = Patient_file::where('patient_id',$request->patient_id)->first();
        if(!$department){
            echo response()->json(['message'=>'no file to this patient']);

        }
        $dep_id = $department->department_id;
        $dep_name = Department::where('id',$dep_id)->value('name');
        if(!$dep_id)
        {
            echo response()->json(['message'=>'this patient is not transfering to any department']);

        }
        return response()->json(['patient_info : '=>$patient_info,'other'=>$department, 'last_department'=>$dep_name],200);     
    }


    public function test_result(Request $request)
    {
        $patient_file = Patient_file::where('patient_id',$request->patient_id)->where('department_id',$request->department_id)->first();
        if(!$patient_file)
        {
            return response()->json(['message'=>'this patient file is not exist !'],401);
        }
        $update []= Patient_file::where('patient_id',$request->patient_id)->where('department_id',$request->department_id)->update([
            'test_result'=>$request->test_result,
        ]);
        
        return response()->json(['message'=>'Test result attached successfully!']);
        
    }



    public function X_ray_result (Request $request)
    {
        $patient_file = Patient_file::where('patient_id',$request->patient_id)->where('department_id',$request->department_id)->first();
        if(!$patient_file)
        {
            return response()->json(['message'=>'this patient file not exist!'],401);
        }
        $update[] = Patient_file::where('patient_id',$request->patient_id)->where('department_id',$request->department_id)->update([
            'X_ray_result'=>$request->X_ray_result,
        ]);
        return response()->json(['message'=>'X-ray attached successfully!']);
    }



    public function searchbypatient(Request $request)
{
    $patient_name = $request->input('patient_name');
    $search = Patient::where('full_name','LIKE','%'.$patient_name.'%')->get();

    return response()->json($search,200);
}
}
