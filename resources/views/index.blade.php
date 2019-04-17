@extends('layout')

@section('title', 'OurPixels')

@section('head')
	<script src="{{ URL::asset('js/renderer.js', true) }}"></script>
	<script>
		$(document).ready(() => {
			$('canvas').each(function () {
				$(this).height($(this).width());
				renderToScreen($(this)[0], $(this).attr('image-data'), $(this).attr('image-size'));
			});
		});
	</script>
@endsection

@section('main')
@if (Auth::check())
<div class="row">
<form action="/image/create" method="post">
	@csrf
	<div class="input-field col s6">
		<i class="material-icons prefix">title</i>
		<input type="text" id="title" name="title" class="autocomplete {{$errors->first('title') ? 'invalid' : ''}}"
			value="{{old('title')}}">
		<label for="title">Title</label>
		<span class="helper-text" data-error="{{$errors->first('title')}}"></span>
	</div>
	<div class="input-field col s2">
		<select name="size">
			@foreach ([8, 16, 32, 48, 64, 128] as $i)
			<option value="{{$i}}" {{old('size') == $i ? 'selected' : ''}}>{{$i}} x {{$i}}</option>
			@endforeach
		</select>
	</div>
	<div class="input-field col s4">
		<button type="submit" class="btn waves-effect green darken-2">Create Pixel Artwork<i class="material-icons right">send</i></button>
	</div>
</form>
</div>
@endif
<div class="row">
	@foreach ($images as $image)
	<div class="col s6 m4">
		<div class="card">
			<div class="card-image">
				<a href="/image/{{ $image->ImageId }}">
					<canvas class="image-canvas" image-data="{{ $image->Data }}" image-size="{{ $image->Size }}"></canvas>
				</a>
			</div>
			<div class="card-content">
				<span class="card-title">{{ $image->Name }}</span>
				<p><a href="/user/{{ $image->creator->UserId }}">{{ $image->creator->Username }}</a></p>
			</div>
		</div>
	</div>
	@endforeach
</div>

<ul class="pagination">
	<li class="{{ $images->onFirstPage() ? 'disabled' : 'waves-effect'}}"><a href="{{ $images->onFirstPage() ? '#' : $images->previousPageUrl() }}"><i class="material-icons">chevron_left</i></a></li>
	@for ($i = 1; $i <= $images->lastPage(); $i++)
		<li class="{{$i == $images->currentPage() ? 'active' : 'waves-effect'}}">
			<a href="/?page={{ $i }}">{{$i}}</a>
		</li>
	@endfor
	<li class="{{ $images->currentPage() == $images->lastPage() ? 'disabled' : 'waves-effect'}}"><a href="{{ $images->currentPage() == $images->lastPage() ? '#' : $images->nextPageUrl() }}"><i class="material-icons">chevron_right</i></a></li>
</ul>
@endsection