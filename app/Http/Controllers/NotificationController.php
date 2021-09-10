<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
class NotificationController extends Controller
{
    //
    public function sendSmsNotificaition()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://91.204.239.44/broker-api/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "messages": 
                {
                    "recipient": "998901371500",
                    "message-id": "mabc000000005",
                    "sms": {
                        "originator": "2800",
                        "content": {
                            "text": "Hurmatli mijoz! Sizning Тuronbankdan olgan kreditingiz bo’yicha muddati o’tgan qarzdorligingiz to’g’risida Talabnoma jo’natilmoqda. Qarzdorligingiz to’g’risida ma’lumotni batafsil quyidagi havola orqali tanishishingiz mumkin!!! https://online.turonbank.uz:4343/client/hash"
                        }
                    }
                }
            
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic dHVyb25iYW5rMjplNzZyS1IzTGky',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return back()->with('message',$response);
    }

}
