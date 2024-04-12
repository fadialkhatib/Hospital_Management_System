<?php

namespace App\Http\Controllers;

use App\Models\ActiveToken;
use App\Models\Department;
use App\Models\Patient;
use App\Models\Patient_file;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function all_deps()
    {
        return Department::all();
    }


    public function show_dep(Request $request)
    {
        $dep = Department::where('id',$request->id)->first();
        return response()->json(['dep_details'=>$dep]);
    }


    public function all_p_in_dep(Request $request)
    {
        try{
        $patients = Patient_file::where('department_id',$request->department_id)->get();
        foreach($patients as $patient)
        {
            $patient_id = $patient->patient_id;
            $patient_name = Patient::where('id',$patient_id)->value('full_name');
            $patient_data []= ['patient_id'=>$patient_id,'patient_name'=>$patient_name];
        }
    }catch(\Exception $e)
    {
        return response()->json($e->getMessage());

    }
        return response()->json($patient_data);
    }   



    public function accept_resident(Request $request)
    {
        try{
            //$token=json_decode(base64_decode($request->header('token')));
        $patient = Patient_file::where('patient_id',$request->patient_id)
                               ->where('department_id',$request->department_id)
                               ->first();
        if(!$patient)
        {
            return response()->json(['messgae'=>'this patient is not in this department or not exist'],401);
        }                     
        if($patient->resident == 'yes')
        {
            return response()->json(['messgae'=>'this patient is resident in this department'],401);
        }  
        $resident = Patient_file::where('patient_id',$request->patient_id)
                                ->where('department_id',$request->department_id)
                                ->update([
                                    'resident'=>'yes'
                                ]);
                            }catch(\Exception $e)
                            {
                                return response()->json($e->getMessage());
                            }
            return response()->json(['message'=>'patient accepted as a resident in this department  '. $request->department_id. ' '],200);         
    }

    public function get_residents(Request $request)
    {
        try{
        $residents = Patient_file::where('department_id',$request->department_id)
                                 ->where('resident','yes')->get();
        
        foreach ($residents as $resident)
        {
            $info = $resident;
            $patient_name = Patient::where('id',$resident->patient_id)->value('full_name');
            $data[]  = ['information '=>$info, ' resident patient '=>$patient_name];
        }
    }catch(\Exception $e)
    {
        return response()->json($e->getMessage());
    }
        return response()->json($data);
    }
}
