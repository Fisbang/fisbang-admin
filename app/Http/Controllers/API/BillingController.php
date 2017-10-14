<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Billing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingController extends Controller
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
			$result = Billing::where('owner_id', $request->user()->id)->get()->toArray();
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
			$validator = Validator::make($request->only('billing_date', 'total', 'power_consumption'), [
				'billing_date' => 'required',
				'total' => 'required',
				'power_consumption' => 'required',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				$result = Billing::create([
					'billing_date' => $request->billing_date,
					'total' => $request->total,
					'power_consumption' => $request->power_consumption,
					'owner_id' => $user->id,
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
			$result = Billing::find($id)->toArray();
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
			$validator = Validator::make($request->only('billing_date', 'total', 'power_consumption'), [
				'billing_date' => 'required',
				'total' => 'required',
				'power_consumption' => 'required',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				$billing = Billing::find($id);
				
				$billing->billing_date = $request->billing_date;
				$billing->total = $request->total;
				$billing->power_consumption = $request->power_consumption;

				$billing->save();
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
			Billing::destroy($id);
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_destroy_entry'
			]], 500);
		}
		
		return response()->json(['result' => ['success' => 'true']], 200);
    }
}
