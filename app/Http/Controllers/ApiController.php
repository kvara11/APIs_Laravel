<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    
    public function index(){
        
        $api_url = 'https://api.thecatapi.com/v1/breeds';
        $json_data = file_get_contents($api_url);
        $response_data = json_decode($json_data);
        $ret = array();

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_URL, 'https://api.thecatapi.com/v1/breeds');
        // $data = curl_exec($ch);
        // curl_close($ch);
        
        foreach ($response_data as $key => $value) {
            if ($response_data[$key]->dog_friendly > 4 && $response_data[$key]->intelligence > 4 && $response_data[$key]->child_friendly > 4) {
                array_push($ret, $response_data[$key]);
            }
        }


        // for ($i=0; $i < count($response_data); $i++) {
        //     if ($response_data[$i]->dog_friendly > 4 && $response_data[$i]->intelligence > 4 && $response_data[$i]->child_friendly > 4) {
        //         array_push($ret, $response_data[$i]);
        //     }
        // }
        
        return response()->json([
            'success' => true,
            'data' =>  $ret
           ], 200);
    }

    public function show(Request $request){
        $api_url = 'https://api.thecatapi.com/v1/breeds';
        $json_data = file_get_contents($api_url);
        $response_data = json_decode($json_data);

        $ret = $response_data[$request]
    }
}


