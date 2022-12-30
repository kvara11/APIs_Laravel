<?php

namespace App\Http\Controllers;

use App\Models\Cat_Details;
use App\Models\Cats;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    
    public function catsAPI(){
        
        $api_url = 'https://api.thecatapi.com/v1/breeds';
        $json_data = file_get_contents($api_url);
        $response_data = json_decode($json_data);
        $ret = array();

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_URL, 'https://api.thecatapi.com/v1/breeds');
        // $data = curl_exec($ch);
        // curl_close($ch);
        
        foreach ($response_data as $key) {
            if ($response_data[$key]->dog_friendly > 4 && $response_data[$key]->intelligence > 4 && $response_data[$key]->child_friendly > 4) {
                array_push($ret, $response_data[$key]);
            }
        }

        return response()->json([
            'success' => true,
            'data' =>  $ret
           ], 200);
    }

    public function show($id){
        $api_url = 'https://api.thecatapi.com/v1/breeds';
        $json_data = file_get_contents($api_url);
        $response_data = json_decode($json_data);

        $ret = $response_data;
        
        //check if id exists in array
        if(isset($response_data[$id])){
            return response()->json([
                'success' => true,
                'data' =>  $ret
            ], 200);
        }else {
            return response()->json([
                'success' => false,
                'data' =>  'no id found'
            ], 404);
        }
    }

    public function store(Request $request){

        $req = array(   
            'name' => $request->get('name'),
            'city' => $request->get('city'),
            'color' => $request->get('color')
        );

        if ($req) {
            $cat = Cats::create($req);
            $detail = Cat_Details::create(['cat_id' => $cat->id, 'height' => $request->get('height'), 'weight' => $request->get('weight')]);
        }

        if($cat && $detail){
            return response()->json([
                'success' => true,
                'data' => $cat,
                'data_details' => $detail
            ], 201);
        }else{
            return response()->json([
                'success' => false
            ], 400);
        }
    }
}