<?php

namespace App\Http\Controllers\API;

use JWTAuth;
use Google;
use App\User;
use Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{	
    /**
     * Authenticate user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function authenticate(Request $request)
    {
		// grab credentials from the request
        $credentials = $request->only('email', 'password');
		$using_social = false;
		$user = null;
		
		try {
			if($request->has('facebook_token')){
				$providerUser = Socialite::driver('facebook')->stateless()->userFromToken($request->facebook_token);
				$user = User::where('email', $providerUser->getEmail())->first();
				if($user == null)
					$user = User::create([
						"email" => $providerUser->getEmail(),
						"name" => $providerUser->getName(),
						"password" => bcrypt($request->facebook_token),
					]);
				
				$using_social = true;
			} else if($request->has('google_token')){
				$googleClient = Google::getClient();
				$payload = $googleClient->verifyIdToken($request->google_token);
				if ($payload) {
					$user = User::where('email', $payload['email'])->first();
					if($user == null)
						$user = User::create([
							"email" => $payload['email'],
							"name" => $payload['name'],
							"password" => bcrypt($request->google_token),
						]);
					
					$using_social = true;
				} else {
					return response()->json(['error' => [
						'code' => 401,
						'message' => 'invalid_credentials'
					]], 401);
				}
			} else {
				// not accepting null or empty password if not using social login
				if($request->has('password') && $request->password == null)
					return response()->json(['error' => [
						'code' => 401,
						'message' => 'invalid_credentials'
					]], 401);
			}
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 400,
				'message' => 'invalid_social_token'
			]], 400);
		}

        try {
			// attempt to verify the credentials and create a token for the user
			if($using_social) {
				if (! $token = JWTAuth::fromUser($user)) {
					return response()->json(['error' => [
						'code' => 401,
						'message' => 'invalid_credentials'
					]], 401);
				}
				
				// it's stateless so no login
				//auth()->login($user);
			} else {
				if (! $token = JWTAuth::attempt($credentials)) {
					return response()->json(['error' => [
						'code' => 401,
						'message' => 'invalid_credentials'
					]], 401);
				}
			} 
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_create_token'
			]], 500);
        }

        // all good so return the token
        return response()->json(['result' => compact('token')]);
    }
	
	/**
     * Refresh user token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function refresh(Request $request)
    {
		$token = JWTAuth::getToken();
		if(!$token){
			return response()->json(['error' => [
				'code' => $exception->getStatusCode(),
				'message' => 'token_invalid'
			]], $exception->getStatusCode());
		}
		try{
			$token = JWTAuth::refresh($token);
		}catch(TokenInvalidException $e){
			return response()->json(['error' => [
				'code' => $exception->getStatusCode(),
				'message' => 'token_invalid'
			]], $exception->getStatusCode());
		}catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_create_token'
			]], 500);
        }

        // all good so return the token
        return response()->json(['result' => compact('token')]);
	}
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$using_social = false;
		$data = [
			"email" => $request->email,
			"name" => $request->name,
			"password" => $request->password,
		];
		
		try {
			if($request->has('facebook_token')){
				$providerUser = Socialite::driver('facebook')->stateless()->userFromToken($request->facebook_token);
				$data = [
					"email" => $providerUser->getEmail(),
					"name" => $providerUser->getName(),
					"password" => "",
				];
				
				$using_social = true;
			} else if($request->has('google_token')){
				$googleClient = Google::getClient();
				$payload = $googleClient->verifyIdToken($request->google_token);
				if ($payload) {
					$data = [
						"email" => $providerUser['email'],
						"name" => $providerUser['name'],
						"password" => "",
					];
					
					$using_social = true;
				} else {
					return response()->json(['error' => [
						'code' => 400,
						'message' => 'invalid_social_token'
					]], 400);
				}
			}
		} catch(Exception $ex) {
            return response()->json(['error' => [
					'code' => 400,
					'message' => 'invalid_social_token'
				]], 400);
		}
		
		if($using_social) {
			$validator = Validator::make($data, [
				'name' => 'required',
				'email' => 'required|email|unique:users',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				User::create([
					'name' => $data['name'],
					'email' => $data['email'],
					'password' => bcrypt($data['password']),
				]);
			}
		} else {			
			$validator = Validator::make($data, [
				'name' => 'required|max:255',
				'email' => 'required|email|max:255|unique:users',
				'password' => 'required|min:6',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				User::create([
					'name' => $data['name'],
					'email' => $data['email'],
					'password' => bcrypt($data['password']),
				]);
			}
		}
		
		return $this->authenticate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
		try {
			$result = User::with('buildings', 'questions')->find($request->user()->id)->toArray();
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
			$validator = Validator::make($request->only('name', 'email', 'password'), [
				'name' => 'max:255',
				'email' => 'email|max:255|unique:users,email,'.$request->user()->id,
				'password' => 'min:6',
			]);
			
			if ($validator->fails()) {
				return response()->json(['error' => [
					'code' => 400,
					'message' => $validator->errors()->first()
				]], 400);
			} else {
				if($request->hasFile('avatar')){
					$path = $request->file('avatar')->store('avatars', 'public');
					$url = Storage::disk('public')->url($path);
				}
				$user = User::find($request->user()->id);
				
				if(isset($request->name))
					$user->name = $request->name;
				
				if(isset($request->email))
					$user->email = $request->email;
				
				if(isset($request->password))
					$user->password = bcrypt($request->password);
				
				if(isset($url))
					$user->avatar = $url;
				
				$user->save();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
			User::destroy($request->user()->id);
		} catch(Exception $ex) {
            return response()->json(['error' => [
				'code' => 500,
				'message' => 'could_not_destroy_entry'
			]], 500);
		}
		
		return response()->json(['result' => ['success' => 'true']], 200);
    }
}
