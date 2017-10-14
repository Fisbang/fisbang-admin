<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Message;
use App\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
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
			$validator = Validator::make($request->only('question_id', 'content'), [
				'question_id' => 'required',
				'content' => 'required',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				$message = Message::create([
					'question_id' => $request->question_id,
					'content' => $request->content,
					'sender_id' => $user->id,
				]);
				
				if($request->hasFile('attachment')){
					$path = $request->file('attachment')->store('images', 'public');
					$url = Storage::disk('public')->url($path);
					
					MessageAttachment::create([
						'message_id' => $message->id,
						'path' => $url,
					]);
				}
				
				$result = Message::with('attachments')->find($message->id)->toArray();
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
			$result = Message::with('attachments', 'user')->find($id)->toArray();
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
			$validator = Validator::make($request->only('content'), [
				'content' => 'required',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				$message = Message::find($id);
				$message->content = $request->content;

				$message->save();
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
			// TODO: delete images
			
			Message::destroy($id);
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_destroy_entry'
			]], 500);
		}
		
		return response()->json(['result' => ['success' => 'true']], 200);
    }
}
