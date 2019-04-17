@extends('layout')

@section('title', 'Ourpixels - ' . ($image ? $image->Name : 'Not Found'))

@section('head')
	<script src="{{ URL::asset('js/renderer.js') }}"></script>
	<script>
		const token = "{{ $token }}";
		const username = "{{ $username }}";
		const imageid = {{ $image ? $image->ImageId : '-1' }};
		$(document).ready(() => {
			if ($('#canvas')) {
				renderToScreen($('#canvas')[0], $('#canvas').attr('image-data'), $('#canvas').attr('image-size'));
			}
		});
	</script>
	@if ($iseditor)
	<script src="{{ URL::asset('js/editor.js') }}"></script>
	@endif
@endsection

@section('main')

@if ($image)
	<h2>{!! $image->Name !!} <a class="creator" href="/user/{{ $image->creator->UserId }}">{{ $image->creator->Username }}</a></h2>
	<div class="row">
		<div class="col s12">
			<canvas id="canvas" width="512px" height="512px" image-data="{{ $image->Data }}" image-size="{{ $image->Size }}"></canvas>
		</div>
	</div>
	@if ($iseditor)
	<div class="row">
		<div class="input-field col s12">
			Color: <input type="color" class="btn" id="color" style="width: 100px; margin-right: 30px;"/>
			<button type="button" class="waves-effect btn yellow darken-2 tooltipped" onclick="saveImage()" data-position="top" data-tooltip="Saved!" id="save">Save Image<i class="material-icons right">save</i></button>
		</div>
	</div>
	@endif
	@if ($modifiable)
	<div class="row">
		<form method="post" action="/image/{{$image->ImageId}}/delete" onsubmit="return confirm('Do you really want to delete this image?');">
			@csrf
			<button type="submit" class="waves-effect btn red">Delete This Image<i class="material-icons right">delete_forever</i></button>
		</form>
	</div>
	<div class="row">
		<form class="col s12" method="post" action="/image/{{$image->ImageId}}/edit">
			@csrf
			<div class="row">
				<div class="input-field col s9">
					<input value="{{ old('title') ? old('title') : addslashes($image->Name) }}" name="title" id="title" type="text" class="validate autocomplete {{$errors->first('title') ? 'invalid' : ''}}">
					<label for="title">Title</label>
					<span class="helper-text" data-error="{{$errors->first('title')}}"></span>
				</div>
				@if ($modifiable)
				<div class="input-field col s3">
					<button type="submit" class="waves-effect btn">Submit<i class="material-icons right">send</i></button>
				</div>
				@endif
			</div>
		</form>
	</div>
	<div class="row">
		<form class="col s12" method="post" action="/image/{{$image->ImageId}}/collab">
			@csrf
			<div class="row">
				<div class="input-field col s5">
					<div>
						<select name="actiontype">
							<option value="add" {{old('actiontype') == 'add' ? 'selected' : ''}}>Add</option>
							<option value="remove" {{old('actiontype') == 'remove' ? 'selected' : ''}}>Remove</option>
						</select>
						<label>Manage Collaborators</label>
					</div>
				</div>
				<div class="input-field col s4">
					<input value="{{old('id')}}" id="coid" name="id" type="text" class="autocomplete validate {{$errors->first('user') ? 'invalid' : ''}}">
					<label for="coid">Collaborator ID / Username</label>
					<span class="helper-text" data-error="{{$errors->first('user')}}"></span>
				</div>
				<div class="input-field col s3">
					<button type="submit" class="waves-effect btn">Submit<i class="material-icons right">send</i></button>
				</div>
			</div>
		</form>
	</div>
	@endif
	<h4>Collaborators</h4>
	<div class="row">
		<ul class="collection" id="collaborators">
			@foreach ($editors as $collab)
			<li class="collection-item" data-id="{{$collab->UserId}}"><a href="/user/{{$collab->UserId}}">{{$collab->Username}}</a></li>
			@endforeach
		</ul>
	</div>
@else
	<h2>Image Not Found.</h2>
@endif

@endsection