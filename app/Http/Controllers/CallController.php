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
        $response = new Twiml();
//        $callerIdNumber = config('services.twilio')['number'];
        $callerIdNumber = env('TWILIO_NUMBER');

        $payload = new Payload();
        $payload->data = json_encode($callerIdNumber);
        $payload->save();


        $dial = $response->dial(['callerId' => $callerIdNumber]);

        $phoneNumberToDial = $request->input('phoneNumber');

        if (isset($phoneNumberToDial)) {
            $dial->number($phoneNumberToDial);
        } else {
            $dial->client('support_agent');
        }

        $payload = new Payload();
        $payload->data = json_encode($response);
        $payload->save();

        return $response;
    }
}
