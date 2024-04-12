<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Process;
use App\Http\Middleware\HandshakeMeddleware;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stringable;

class SessionController extends Controller
{
    public function startSession(Request $request)
    {
        $macadress = $request->input('MACadress');
        $check = Session::where('MACadress',$macadress)->first();

        if(!$check)
        {
            return response()->json(['message'=>'this MacAdress is not exist!']);
        }
        //لعدم تخزين بيانات اكثر من اللازم
        $check->delete();


        $unique = uniqid();
        $encode=json_encode($unique);
        $sessionKey=base64_encode($encode);
        $check = Session::where('sessionKey',$sessionKey)->first();

        if($check){
            return response()->json(['message'=>'The session is already opened']);
        }

        
        session(['sessionKey' => $sessionKey]);
        if (!$sessionKey){
        return response()->json(['ErrorMessage'=>'session Error']);
}

        
            $store = new Session();
            $store->sessionKey = $sessionKey;
            $store->MACadress = $macadress;
            $store->save();

        

    return response()->json(['sessionKey' => $sessionKey,'MACadress'=>$macadress  ]);   

}
}
