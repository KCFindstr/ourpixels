@extends('layout')

@section('title',  ($user ? $user->Username . '\'s Artworks' : 'No Such User'))

@section('head')
	<script src="{{ URL::asset('js/renderer.js') }}"></script>
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

@if ($user)
	<h2>{{ $user->Username }}'s Artworks</h2>
	<div class="row">
		@forelse ($images as $image)
		<div class="col s6 m4">
			<div class="card">
				<div class="card-image">
					<a href="/image/{{ $image->ImageId }}">
						<canvas class="image-canvas" image-data="{{ $image->Data }}" image-size="{{ $image->Size }}"></canvas>
					</a>
				</div>
				<div class="card-content">
					<span class="card-title">{{ $image->Name }}</span>
				</div>
			</div>
		</div>
		@empty
			<p>Looks like {{ $user->Username }} has not created anything yet!</p>
		@endforelse
	</div>
@else
	<h2>No Such User.</h2>
@endif

@endsection