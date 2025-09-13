<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ConversionController extends Controller
{
    public function main_page(){
        return view ('tool');
        //Shows the main page
    }

    public function convert(Request $request){
        try {
            //Get input from conversion form
            $text = $request->input("Local_text");
            $language = $request->input("Language");
            $speaker = $request->input("Speaker");
            //Get api key and url endpoint
            $conversion_key = env('CONVERSION_API_KEY');
            $conversion_url = 'https://translation-api.ghananlp.org/tts/v1/synthesize';

            //Make api call
           $response = Http::withHeaders([
            //Type of data - json
            'Content-Type' => 'application/json',
            //Api key
            'Ocp-Apim-Subscription-Key' => $conversion_key,
           ])->post($conversion_url, [
            //Post to the endpoint the information below
            'text' => $text,
            'language' => $language,
            'speaker_id' => $speaker,
           ]);

           if ($response->ok()) {
            //Generate name for audio
            $audio_name = 'audio/' . Str::random(10) . '.mp3';
            //Direct where to save audio file
            Storage::disk("public")->put($audio_name, $response->body());
            //Create link to play audio in browser
            $audioUrl = Storage::url($audio_name);

            //Display audio in json form
            return response()->json(["audioUrl" => $audioUrl]);
           } else {
            return response()->json(["error" => 'Conversion API call failed!'], $response->status());
           }
        } catch(\Exception $e) {
            return response()->json(['error' => "An error occured" . $e->getMessage()], 500);
        }
    }
}