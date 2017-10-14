<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Appliance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplianceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		try {
			$result = Appliance::where('building_id', $request->user()->buildings()->first()->id)->get()->toArray();
			return response()->json(['result' => $result]);
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_list_entries'
			]], 500);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		try {
			$user = $request->user();
			$validator = Validator::make($request->only('building_id', 'name', 'type', 'power', 'brand', 'brand_type'), [
				'building_id' => 'required',
				'name' => 'required|max:255',
				'type' => 'required|max:255',
				'brand' => 'max:255',
				'brand_type' => 'max:255',
				'power' => 'required',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				$result = Appliance::create([
					'name' => $request->name,
					'type' => $request->type,
					'power' => $request->power,
					'building_id' => $request->building_id,
					'brand' => $request->brand,
					'brand_type' => $request->brand_type,
				])->toArray();
				return response()->json(['result' => $result]);
			}
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_insert_entry'
			]], 500);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
		try {
			$result = Appliance::find($id)->toArray();
			return response()->json(['result' => $result]);
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_fetch_details'
			]], 500);
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		try {
			$validator = Validator::make($request->only('building_id', 'name', 'type', 'power', 'brand', 'brand_type'), [
				'building_id' => 'required',
				'name' => 'required|max:255',
				'type' => 'required|max:255',
				'brand' => 'max:255',
				'brand_type' => 'max:255',
				'power' => 'required',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				$appliance = Appliance::find($id);
				
				$appliance->name = $request->name;
				$appliance->type = $request->type;
				$appliance->power = $request->power;
				$appliance->building_id = $request->building_id;
				$appliance->brand = $request->brand;
				$appliance->brand_type = $request->brand_type;

				$appliance->save();
			}
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_update_entry'
			]], 500);
		}
		
		return response()->json(['result' => ['success' => 'true']], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		try {
			Appliance::destroy($id);
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_destroy_entry'
			]], 500);
		}
		
		return response()->json(['result' => ['success' => 'true']], 200);
    }
}
