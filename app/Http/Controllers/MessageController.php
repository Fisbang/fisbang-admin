<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Session;
use App\User;
use App\Message;
use App\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'content' => 'required',
            'question_id' => 'required',
            'sender_id' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('questions/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $message = new Message;
            $message->question_id = Input::get('question_id');
            $message->content = Input::get('content');
            $message->sender_id = Input::get('sender_id');
            $message->save();
			
			if($request->hasFile('attachment')){
				$path = $request->file('attachment')->store('images', 'public');
				$url = Storage::disk('public')->url($path);
				
				MessageAttachment::create([
					'message_id' => $message->id,
					'path' => $url,
				]);
			}

            // redirect
            Session::flash('message', 'Successfully created message!');
            return Redirect::to('questions/'.$message->question_id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
