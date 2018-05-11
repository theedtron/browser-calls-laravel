<?php

namespace App\Http\Controllers;

use App\Payload;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Twilio\Twiml;

class CallController extends Controller
{
    /**
     * Process a new call
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newCall(Request $request)
    {
        Payload::create([
            'data' => json_encode($request->all())
        ]);

        $response = new Twiml();
        $callerIdNumber = config('services.twilio')['number'];
//        $callerIdNumber = env('TWILIO_NUMBER');

        Payload::create([
            'data' => json_encode($callerIdNumber)
        ]);


        $dial = $response->dial(['callerId' => $callerIdNumber]);

        $phoneNumberToDial = $request->input('phoneNumber');

        if (isset($phoneNumberToDial)) {
            $dial->number($phoneNumberToDial);
        } else {
            $dial->client('support_agent');
        }

        Payload::create([
            'data' => json_encode($response)
        ]);

        return $response;
    }
}
