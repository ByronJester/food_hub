<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSms($to, $message)
    {
        // $account_sid = 'AC0201237e8b04951bfbcdb9db86613254';
        // $auth_token = 'f3e32661062146402c7427e0a2dc3aed';
        // $twilio_number = "+19123043848";

        // $client = new Client($account_sid, $auth_token);

        // $to = '+'  . $to;

        // return $client->messages->create(
        //     $to,
        //     array(
        //         'from' => $twilio_number,
        //         'body' => $message
        //     )
        // );

        $ch = curl_init();

        $parameters = array(
            'apikey' => '3763c09ae9280c98876389affa1ad5fc', //Your API KEY
            'number' => $to,
            'message' => $message,
            'sendername' => 'SEMAPHORE'
        );

        curl_setopt( $ch, CURLOPT_URL,'https://semaphore.co/api/v4/messages' ); 
        curl_setopt( $ch, CURLOPT_POST, 1 );

        //Send the parameters set above with the request
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );

        // Receive response from server
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $output = curl_exec( $ch );
        curl_close ($ch);

        //Show the server response
        return $output;
    }

    public function getPublicKey($key)
    {
        return base64_encode($key);
    }

    public function getSecretKey($key)
    {
        return base64_encode($key);
    }

    public function createSource($amount, $public_key, $secret_key)
	{
        $auth = Auth::user();

		$body = [
			"data" => [
				"attributes" => [
					"amount" => $amount,
					"type" => "gcash",
					"currency" => "PHP",
					"redirect" => [
						"success" => url('paymongo/success'),
						"failed" => url('paymongo/failed')
                    ],
                    "billing" => [
                        "name" => $auth->name,
                        "phone" => $auth->phone
                    ]
				]
			]
		];

		$client = new \GuzzleHttp\Client(['verify' => false]);

		$response = $client->request('POST', 'https://api.paymongo.com/v1/sources', [
			'body' => json_encode($body),
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => "Basic " . $public_key,
				'Content-Type' => 'application/json',
			],
		]);

		$body = json_decode($response->getBody());
		$source = $body->data->attributes;

		session()->put('source_id', $body->data->id);
		session()->put('public_key', $public_key);
		session()->put('secret_key', $secret_key);


		// Booking::where('booking_id', $booking_id)->update([ 'source_id' => $body->data->id ]);
		
		return $source;
	}

    public function retrieveSource($source_id)
	{
		$secretKey = session()->get('secret_key');

		$client = new \GuzzleHttp\Client(['verify' => false]);

		$response = $client->request('GET', "https://api.paymongo.com/v1/sources/{$source_id}", [
			'headers' => [
				'accept' => 'application/json',
				'authorization' => "Basic " . $secretKey,
			],
		]);

		return json_decode($response->getBody());
	}

    public function createPayment()
	{
		$source_id = session()->get('source_id');
		$secret_key = session()->get('secret_key');

		$source = $this->retrieveSource($source_id);
		
		if (
			isset($source->data)
			&& isset($source->data->attributes)
			&& isset($source->data->attributes->status)
			&& $source->data->attributes->status == 'chargeable'
		) {
			$amount = $source->data->attributes->amount;

			$client = new \GuzzleHttp\Client(['verify' => false]);

			$response = $client->request('POST', 'https://api.paymongo.com/v1/payments', [
				'body' => '{"data":{"attributes":{"amount":' . $amount . ',"source":{"id":"' . $source_id . '","type":"source"},"currency":"PHP","description":"G-Cash Payment"}}}',
				'headers' => [
					'accept' => 'application/json',
					'authorization' => "Basic " . $secret_key,
					'content-type' => 'application/json',
				],
			]);

			$payment_info = json_decode($response->getBody());
	
			if ($payment_info->data->attributes->status == 'paid') {
				return $payment_info; 

			} else {
				return null; 
			}
		} else {
			return null; 
		}
	}
}
