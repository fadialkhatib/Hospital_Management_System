<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Testqueue;
use App\Models\Xrayqueue;
use Illuminate\Http\Request;
use Symfony\Component\Mailer\DelayedEnvelope;

class QueueController extends Controller
{
    ########   Test Queue   ########

    public function request_test(Request $request)
    {
        try{
        $patient = Patient::where('id',$request->patient_id)->first();
        if(!$patient)
        {
            return response()->json(['message'=>'this patient is not exist!'],401);
        }
        $add_to_queue = Testqueue::create([
            'patient_id'=>$patient->id
        ]);
    }catch(\Exception $e)
    {
        return response()->json($e->getMessage());
    }
        return response()->json(['message'=>'the patient will be in the queue please go to the tast department !'],200);
    }



    public function all_queue_patients()
    {
        return Testqueue::all();
    }


    public function get_p_from_queue(Request $request)
    {
        try{
        $patient = Testqueue::where('patient_id',$request->patient_id)->first();
        if(!$patient)
        {
            return response()->json(['messgae'=>'this patient is out of the queue'],401);
        }
        $patient_name = Patient::where('id',$patient->patient_id)->value('full_name');
        sleep(10);
        $patient->delete();
    }catch(\Exception $e)
    {
        return response()->json($e->getMessage());
    }
        return response()->json(['message'=>'this patient with name '.$patient_name.'   test is ready!'],200);
    }




    ############# X-RAY QUEUE   #################
    public function request_xray(Request $request)
    {
        try{
        $patient = Patient::where('id',$request->patient_id)->first();
        if(!$patient)
        {
            return response()->json(['message'=>'this patient is not exist!'],401);
        }
        $add_to_queue = Xrayqueue::create([
            'patient_id'=>$patient->id
        ]);
    }catch(\Exception $e)
    {
        return response()->json($e->getMessage());
    }
        return response()->json(['message'=>'the patient will be in the queue please go to the tast department !'],200);
    }



    public function all_xqueue_patients()
    {
        return Xrayqueue::all();
    }


    public function get_p_from_xqueue(Request $request)
    {
        try{
        $patient = Xrayqueue::where('patient_id',$request->patient_id)->first();
        if(!$patient)
        {
            return response()->json(['messgae'=>'this patient is out of the queue'],401);
        }
        $patient_name = Patient::where('id',$patient->patient_id)->value('full_name');
        sleep(10);
        $patient->delete();
    }catch(\Exception $e)
    {
        return response()->json($e->getMessage());
    }
        return response()->json(['message'=>'this patient with name '.$patient_name.'   x-ray is ready!'],200);
    }
}
