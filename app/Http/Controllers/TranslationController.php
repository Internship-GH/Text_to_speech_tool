<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class TranslationController extends Controller
{
    public function translate(Request $request){
        //Url and api key
        $trans_key = env('CONVERSION_API_KEY');
        $trans_url = 'https://translation-api.ghananlp.org/v1/translate';

        //Get input from form
        $from = $request->input('from');
        $to = $request->input('to');
        $text = $request->input('trans_text');

        try{
            //Send data to api
            $response = Http::timeout(10)->
            withHeaders([
                //Type of data
                'Content-Type' => 'application/json',
                //API key
                'Ocp-Apim-Subscription-Key' => $trans_key
            ])->post(
                $trans_url,
                [
                    //Post to endpoint the data below
                    'in' => $text,
                    'lang' => $from.'-'.$to
                ]
            );

            


            if ($response->ok()){
                //Store as variable as API call returns a string
                $string = $response->body();

                return response()->json([
                    'success' => true,
                    'translated_text' => $string
                ]);
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'error' => $response->body()
                    ],
                    $response->status()
                );
            }
        }catch(\Exception $e){
            Log::error('Translate Exception:', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }

        //Wait for response
        //Receive feedback and display it
        //If not available, show it is not available or an error occured
    }

}