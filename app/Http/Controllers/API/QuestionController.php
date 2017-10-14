<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Question;
use App\Message;
use App\MessageAttachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class QuestionController extends Controller
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
			$result = Question::with('messages', 'user', 'messages.user', 'messages.attachments')->where('owner_id', $request->user()->id)->get()->toArray();
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
			$question = Question::create([
				'is_answered' => false,
				'is_premium' => false,
				'owner_id' => $user->id,
			]);
			
			$validator = Validator::make($request->only('content'), [
				'content' => 'required',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				$message = Message::create([
					'question_id' => $question->id,
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
				
				$result = Question::with('messages', 'user', 'messages.user', 'messages.attachments')->find($question->id)->toArray();
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
     * Mark as answered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markasanswered(Request $request, $id)
    {
        try {
			$question = Question::find($id);
			$question->is_answered = true;

			$question->save();
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_update_entry'
			]], 500);
		}
		
		return response()->json(['result' => ['success' => 'true']], 200);
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
			$result = Question::with('messages', 'user', 'messages.user', 'messages.attachments')->find($id)->toArray();
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
			$question = Question::find($id);
			$question->is_answered = $request->is_answered;
			$question->is_premium = $request->is_premium;

			$question->save();
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
			
			Question::destroy($id);
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_destroy_entry'
			]], 500);
		}
		
		return response()->json(['result' => ['success' => 'true']], 200);
    }
}
