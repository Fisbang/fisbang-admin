<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Session;
use App\User;
use App\Article;
use App\ArticleRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
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
        // get all the articles
        $articles = Article::sortable()->paginate(10);

        // load the view and pass the articles
        return view('articles.index')->with('articles', $articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
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
            'title' => 'required',
            'description' => 'required',
            'url' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('articles/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store			
            $article = new Article;
            $article->title = Input::get('title');
            $article->description = Input::get('description');
            $article->url = Input::get('url');
            
			if($request->hasFile('image')){
				$path = $request->file('image')->store('article_images', 'public');
				$article->image = Storage::disk('public')->url($path);
			}
			
            $article->save();

            // redirect
            Session::flash('message', 'Successfully created article!');
            return Redirect::to('articles');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        // show the view and pass the article to it
        return view('articles.show')
            ->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        // show the edit form and pass the article
        return view('articles.edit')->with('article', $article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'url' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('articles/' . $article->id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $article->title = Input::get('title');
            $article->description = Input::get('description');
            $article->url = Input::get('url');
			
			if($request->hasFile('image')){
				// TODO: delete image
				
				$path = $request->file('image')->store('article_images', 'public');
				$article->image = Storage::disk('public')->url($path);
			}
			
            $article->save();

            // redirect
            Session::flash('message', 'Successfully updated article!');
            return Redirect::to('articles/'.$article->id);
        }
    }
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addRecipient(Request $request)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'email' => 'required|email|max:255|exists:users,email',
            'article_id' => 'required|exists:articles,id',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('articles/'.Input::get('article_id'))
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
			$article_id = Input::get('article_id');
			$user_id = User::where('email', Input::get('email'))->first()->id;
			$recipient = ArticleRecipient::where('user_id', $user_id)->where('article_id', $article_id)->first();
			if(isset($recipient))
			{
				Session::flash('message', 'User is already a recipient for the article');
				return Redirect::to('articles/'.$article_id);
			}
			
            // store			
            $recipient = new ArticleRecipient;
            $recipient->user_id = $user_id;
            $recipient->article_id = $article_id;
			
            $recipient->save();

            // redirect
            Session::flash('message', 'Successfully added recipient to the article!');
            return Redirect::to('articles/'.$article_id);
        }
    }
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAllRecipients(Request $request)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'article_id' => 'required|exists:articles,id',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('articles/')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
			$article_id = Input::get('article_id');
			foreach(User::all() as $value){
				$recipient = ArticleRecipient::where('user_id', $value->id)->where('article_id', $article_id)->first();
				if(!isset($recipient))
				{
					// store			
					$recipient = new ArticleRecipient;
					$recipient->user_id = $value->id;
					$recipient->article_id = $article_id;
					
					$recipient->save();
				}
			}

			// redirect
			Session::flash('message', 'Successfully added all recipients to the article!');
			return Redirect::to('articles/'.$article_id);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id, $recipient
     * @return \Illuminate\Http\Response
     */
    public function removeRecipient($id, $user_id)
    {		
        $recipient = ArticleRecipient::where('user_id', $user_id)->where('article_id', $id)->first();
		if(isset($recipient))
			$recipient->delete();

        // redirect
        Session::flash('message', 'Successfully removed the recipient!');
        return Redirect::to('articles/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function removeAll(Article $article)
    {		
		// validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'article_id' => 'required|exists:articles,id',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return Redirect::to('articles/')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
			$article_id = Input::get('article_id');
			$article = Article::find($article_id);
			
			foreach ($article->recipients()->get() as $value){
				$value->delete();
			}

			// redirect
			Session::flash('message', 'Successfully removed all recipients!');
			return Redirect::to('articles/'.$article_id);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        // delete
		// TODO: delete images
		
        $article->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the article!');
        return Redirect::to('articles');
    }
}
