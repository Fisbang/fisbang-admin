<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Session;
use App\User;
use App\Question;
use App\Message;
use App\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class QuestionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the questions
        $questions = Question::sortable()->paginate(10);

        // load the view and pass the questions
        return view('questions.index')->with('questions', $questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create')->with('users', User::pluck('email', 'id'));
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
            'owner_id' => 'required',
            'billing' => 'nullable|numeric',
            'hours' => 'nullable|numeric',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('questions/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $question = new Question;
            $question->is_answered = $request->has('is_answered');
            $question->is_premium = $request->has('is_premium');
			if($request->has('billing'))
				$question->billing = Input::get('billing');
			if($request->has('hours'))
				$question->hours = Input::get('hours');
            $question->owner_id = Input::get('owner_id');
            $question->save();
			
            $message = new Message;
            $message->question_id = $question->id;
            $message->content = Input::get('content');
            $message->sender_id = Input::get('owner_id');
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
            Session::flash('message', 'Successfully created question!');
            return Redirect::to('questions');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        // show the view and pass the question to it
        return view('questions.show')
            ->with('question', $question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        // show the edit form and pass the question
        return view('questions.edit')->with('question', $question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'billing' => 'nullable|numeric',
            'hours' => 'nullable|numeric',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('questions/' . $question->id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $question->is_answered = $request->has('is_answered');
            $question->is_premium = $request->has('is_premium');
			if($request->has('billing'))
				$question->billing = Input::get('billing');
			if($request->has('hours'))
				$question->hours = Input::get('hours');
            $question->save();

            // redirect
            Session::flash('message', 'Successfully updated question!');
            return Redirect::to('questions/'.$question->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
		// TODO: delete images
		/*foreach($question->messages()->get() as $key => $value){
			foreach($value->attachments()->get() as $key2 => $value2){
				File::delete(public_path().'/images/'.basename($value2->path));
			}
		}*/
		
		// delete
        $question->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the question!');
        return Redirect::to('questions');
    }
}
