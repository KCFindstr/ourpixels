@extends('layout')

@section('title', 'Sign In')

@section('head')
<script>
	function runHash() {
		let el = $('#password');
		let password = el.val();
		if (password.length) {
			sha256(password).then((code) => {
				code = code.toUpperCase();
				el.val(code);
				document.forms.signin.submit();
			});
		}
		return false;
	}
</script>
@endsection

@section('main')
<h2>Sign In</h2>
<form name="signin" action="/signin" method="post" onsubmit="return runHash();">
	@csrf
	<div class="row">
		<div class="input-field col s12">
			<i class="material-icons prefix">assignment_ind</i>
			<input type="text" id="username" name="username" class="autocomplete {{$errors->first('username') ? 'invalid' : ''}}"
				value="{{old('username')}}">
			<label for="username">Username</label>
			<span class="helper-text" data-error="{{$errors->first('username')}}"></span>
		</div>
		<div class="input-field col s12">
			<i class="material-icons prefix">fingerprint</i>
			<input type="password" id="password" name="password" class="validate autocomplete {{$errors->first('password') ? 'invalid' : ''}}"
			value="">
			<label for="password">Password</label>
			<span class="helper-text" data-error="{{$errors->first('password')}}"></span>
		</div>
	</div>
	<input type="text" name="token" hidden/>
	<div class="center-btn">
		<button type="submit" class="btn waves-effect">Submit<i class="material-icons right">send</i></button>
	</div>
</form>
@endsection