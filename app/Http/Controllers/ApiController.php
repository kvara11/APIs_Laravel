<?php

namespace App\Http\Controllers;

use App\Models\Cat_Details;
use App\Models\Cats;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ApiController extends Controller
{
    public function api_index()
    {
        //with Get_Content
        $api_url = 'https://api.thecatapi.com/v1/breeds';
        $json_data = file_get_contents($api_url);
        $response_data = json_decode($json_data);
        $ret = array();

        //with CURL
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_URL, 'https://api.thecatapi.com/v1/breeds');
        // $data = curl_exec($ch);
        // curl_close($ch);

        foreach ($response_data as $key => $value) {
            if ($response_data[$key]->dog_friendly >= 4 && $response_data[$key]->intelligence >= 4 && $response_data[$key]->child_friendly >= 4) {
                array_push($ret, $response_data[$key]);
            }
        }

        return response()->json([
            'success' => true,
            'data' =>  $ret
        ], 200);
    }


    public function api_show($id)
    {
        $api_url = 'https://api.thecatapi.com/v1/breeds';
        $json_data = file_get_contents($api_url);
        $response_data = json_decode($json_data);

        //check if id exists in array
        if (isset($response_data[$id])) {
            return response()->json([
                'success' => true,
                'data' =>  $response_data[$id]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' =>  'ID not found!'
            ], 404);
        }
    }


    public function index()
    {
        //with table joins
        // $res = DB::table('cats')
        //     ->select('cats.*', 'cat_details.cat_id', 'cat_details.height', 'cat_details.weight')
        //     ->leftJoin('cat_details', 'cats.id', '=', 'cat_details.cat_id')
        //     ->orderBy('cats.id', 'desc')
        //     ->get();

        //with model elequant - app/Model/Cats/cat_details
        $res = Cats::with('cat_details')->orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $res
        ], 200);
    }

    public function show($id){
        $res = Cats::with('cat_details')->find($id);

        if ($res) {
            return response()->json([
                'success' => true,
                'data' => $res
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $rule = array(
            'name' => 'required|string|max:20',
            'city' => 'required|string|max:25',
            'color' => 'required|string|max:25',
            'height' => 'nullable|string|max:5',
            'weight' => 'nullable|string|max:5',
        );

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return response()->json([
                $validator->errors(),
            ], 401);
        }

        $catForm = array(
            'name' => $request->get('name'),
            'city' => $request->get('city'),
            'color' => $request->get('color')
        );

        $cat = Cats::create($catForm);

        //method 1
        // $detail = Cat_Details::create(['cat_id' => $cat->id, 'height' => $request->get('height'), 'weight' => $request->get('weight')]);

        //method 2
        $detail = new Cat_Details();
        $detail->cat_id = $cat->id;
        $detail->height = $request->get('height');
        $detail->weight = $request->get('weight');
        $cat->cat_details()->save($detail);
    

        if ($cat && $detail) {
            return response()->json([
                'success' => true,
                'data' => $cat,
                'data_details' => $detail
            ], 201);
        } else {
            return response()->json([
                'success' => false
            ], 400);
        }
    }


    public function edit($id)
    {
        $cat = Cats::with('cat_details')->find($id);

        if (isset($cat)) {
            return response()->json([
                'success' => true,
                'data' => $cat
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found'
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        $cat = Cats::find($id);

        if (!isset($cat)) {
            return response()->json([
                'success' => false,
                'data' => 'no data found'
            ], 404);
        }

        $rule = array(
            'name' => 'required|string|max:20',
            'city' => 'required|string|max:25',
            'color' => 'required|string|max:25',
            'height' => 'nullable|string|max:5',
            'weight' => 'nullable|string|max:5',
        );

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return response()->json([
                $validator->errors(),
            ], 401);
        }

        $cat->name = $request->get('name');
        $cat->city = $request->get('city');
        $cat->color = $request->get('color');

        if ($cat->update()) {

            $details = array();
            //check if request contains inputs
            $request->has('height') ? $details['height'] = $request->get('height') : null;
            $request->has('weight') ? $details['weight'] = $request->get('weight') : null;

            if ($cat->cat_details()->update($details)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Updated Successfully',
                    'data' => $cat,
                    'details' => $details
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Update Failed',
            ], 400);
        }

    }

    public function destroy($id)
    {
        $cat = Cats::find($id);

        if ($cat) {
            $cat->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted Successfully, ID:' . $id
            ], 200);
            //204 - status, without message response
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Not Found'
            ], 404);
        }
    }
}