<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(3);
        return view('welcome', ['users' => $users]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_line_1' => 'required',
            'address_line_2' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $address = new Address;
            $address->user_id = $request->id;
            $address->address_line_1 = $request->address_line_1;
            $address->address_line_2 = $request->address_line_2;
            $address->country = $request->country;
            $address->save();
            $json  = json_encode($address);
            return $json;
        }
    }
    public function destroy($id)
    {
        $delete = Address::where('id', $id)->delete();
        return response()->json(['deleted' => "data is deleted"]);
    }
    public function show(Request $request, $id)
    {
        $data = Address::where('user_id', $id)->get();
        return json_encode($data);
    }
    public function get_country(){
        $countries =Country::pluck('name')->all();
        return $countries;
    }
}
