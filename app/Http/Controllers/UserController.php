<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use Illuminate\Contracts\Hashing\Hasher;
use App\User;
use App\Image;

class UserController extends Controller {
	public function index($userId) {
		$user = null;
		$artworks = null;
		if ($userId) {
			$user = User::find($userId);
			if ($user)
				$artworks = $user->artworks;
		}
		return view('user', [
			'user' => $user,
			'images' => $artworks
		]);
	}

	public function signin() {
		return view('signin');
	}

	public function username() {
		return 'Username';
	}

	public function onRegister(Request $request) {
		$input = $request->all();

		$validation = Validator::make($input, [
				'username' => 'required|min:3|max:18|unique:Users,Username|regex:/[a-zA-Z0-9_\-]*/u',
				'password' => 'required'
		]);

		if ($validation->fails()) {
			return redirect('/register')
				->withInput()
				->withErrors($validation);
		}

		$user = new User();
		$user->Username = $request->username;
		$user->Password = $request->password;
		$user->Admin = 0;
		$user->setRememberToken('1');
		$user->save();

		Auth::login($user);
		Auth::logout();
		Auth::login($user);
		return redirect('/');
	}

	public function onSignin(Request $request) {
		$input = $request->all();

		$validation = Validator::make($input, [
				'username' => 'required',
				'password' => 'required'
		]);

		if ($validation->fails()) {
			return redirect('/signin')
				->withInput()
				->withErrors($validation);
		}

		$user = User::where('Username', $request->username)
			->where('Password', $request->password)
			->first();

		if($user) {
			Auth::login($user);
			return redirect('/');
		}
		return redirect('/signin')
			->withInput()
			->withErrors([
				'username' => 'No such user or wrong password',
				'password' => 'No such user or wrong password'
			]);
	}

	public function register() {
		return view('register');
	}

	public function logout() {
		Auth::logout();
		return redirect()->back();
	}
}
