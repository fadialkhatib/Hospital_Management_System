<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent as AgentAgent;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;

class HandshakeMeddleware
{
   
    public function handle(Request $request, Closure $next): Response
    {
        $sessionKey = $request->header('sessionKey');
        $decodedSessionKey = base64_decode($sessionKey);
        $decodedUnique = json_decode($decodedSessionKey);

        $check = Session::where('sessionKey',$sessionKey)->first();
        
        if(!$check)
        {
            return response()->json(['message'=>'this computer cannt do any operation in this site because Not Valid session ']);
        }
        $check2 = $check->MACadress;
        if(!$check)
        {
            return response()->json(['message'=>'this computer is not allowed to use this application ']);

        }
        
            return $next($request);
   

    }
}

