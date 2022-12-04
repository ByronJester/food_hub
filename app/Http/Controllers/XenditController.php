<?php

namespace App\Http\Controllers;

use App\Services\XenditService;
use Illuminate\Http\Request;

class XenditController extends Controller
{
    /**
     * Accept callback from xendit.
     */
    public function notify(Request $request)
    {
        $xendit_service = new XenditService();
        $call_back_token = config('xendit.call_back_key');
        $reqHeaders = $request->header();
        $xIncomingCallbackTokenHeader = isset($reqHeaders['x-callback-token']) ? $reqHeaders['x-callback-token'] : '';

        if(isset($xIncomingCallbackTokenHeader[0]) && $xIncomingCallbackTokenHeader[0] == $call_back_token) 
        {
            if($request->data['status'] == 'SUCCEEDED') 
            {
                $xendit_service->toSuccessful($request);
            } 
            else 
            {
                $xendit_service->toFailed($request);
            }

            return response()->json([
                'message' => 'Ok'
            ], 200);
        }
    }
}
