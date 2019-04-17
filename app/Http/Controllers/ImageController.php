<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\Image;
use Validator;
use Redirect;

class ImageController extends Controller {
	public function index(Request $request) {
		$images = Image::paginate(9);
		return view('index', [
			'images' => $images
		]);
	}

	public function image($imageId) {
		$image = null;
		$modifiable = false;
		$editors = null;
		$iseditor = false;
		$token = 'NONE';
		$username = 'NONE';
		if ($imageId) {
			$image = Image::find($imageId);
			if ($image) {
				$editors = $image->editors;
				if (Auth::check()) {
					$token = Auth::user()->getRememberToken();
					$username = Auth::user()->Username;
					if (Auth::user()->images->contains('ImageId', $imageId))
						$iseditor = true;
					if (Auth::user()->UserId == $image->CreatorId)
						$modifiable = true;
					if (Auth::user()->Admin > 0) {
						$modifiable = true;
					}
				}
			}
		}
		return view('image', [
			'image' => $image,
			'modifiable' => $modifiable,
			'editors' => $editors,
			'iseditor' => $iseditor,
			'token' => $token,
			'username' => $username
		]);
	}
	
	public function delete(Request $request, $id) {
		$input = $request->all();

		if (!Auth::check()) {
			return Redirect::back();
		}

		$user = Auth::user();
		$image = Image::find($id);
		if (!$image) {
			return Redirect::back();
		}
		if ($image->creator != $user && $user->Admin == 0) {
			return Redirect::back();
		}
		DB::table('Access')
			->where('ImageId', '=', $image->ImageId)
			->delete();
		$image->delete();

		return redirect('/');
	}

	public function edit(Request $request, $id) {
		$input = $request->all();

		$validation = Validator::make($input, [
				'title' => 'required|min:3|max:64'
		]);

		if ($validation->fails()) {
			return Redirect::back()
				->withInput()
				->withErrors($validation);
		}

		$image = Image::find($id);
		if (!$image) {
			return Redirect::back();
		}
		$image->Name = $request->title;
		$image->save();

		return Redirect::back();
	}
	
	public function create(Request $request) {
		$input = $request->all();

		if (!Auth::check()) {
			return Redirect::back();
		}

		$validation = Validator::make($input, [
				'title' => 'required|min:3|max:64',
				'size' => 'required|min:8|max:128|integer'
		]);

		if ($validation->fails()) {
			return Redirect::back()
				->withInput()
				->withErrors($validation);
		}

		$image = new Image();
		$image->Name = $request->title;
		$image->Size = $request->size;
		$image->creator()->associate(Auth::user());
		$image->Data = str_repeat('f', $request->size * $request->size * 6);
		$image->save();

		DB::table('Access')
			->insert([
				'UserId' => Auth::user()->UserId,
				'ImageId' => $image->ImageId
			]);

		return redirect('/image/' . $image->ImageId);
	}

	public function collab(Request $request, $id) {
		$input = $request->all();

		$validation = Validator::make($input, [
				'actiontype' => 'required',
				'id' => 'required'
		]);

		if ($validation->fails()) {
			return Redirect::back()
				->withInput()
				->withErrors($validation);
		}

		$image = Image::find($id);
		if (!$image) {
			return Redirect::back();
		}
		
		$user = User::where('UserId', '=', $request->id)
			->orwhere('Username', '=', $request->id)
			->first();
		if (!$user) {
			return Redirect::back()
				->withInput()
				->withErrors([
					'user' => 'No such user.'
				]);
		}

		if ($request->actiontype == 'add') {
			if ($image->editors->contains('UserId', $user->UserId)) {
				return Redirect::back()
					->withInput()
					->withErrors([
						'user' => 'This user is already collaborator.'
					]);
			}
			$image->editors()->attach($user->UserId);
		} else {
			if ($user->UserId == $image->CreatorId) {
				return Redirect::back()
				->withInput()
				->withErrors([
					'user' => 'You cannot remove the creator.'
				]);
			}
			if ($image->editors->contains('UserId', $user->UserId)) {
				DB::table('Access')
					->where('UserId', '=', $user->UserId)
					->where('ImageId', '=', $id)
					->delete();
			} else {
				return Redirect::back()
				->withInput()
				->withErrors([
					'user' => 'This user is not collaborator.'
				]);
			}
		}

		return Redirect::back();
	}
}
