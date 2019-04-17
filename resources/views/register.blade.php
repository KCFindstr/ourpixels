@extends('layout')

@section('title', 'Register')

@section('head')
<script>
	$(document).ready(() => {
		$('input').blur(function () {
			$(this).removeClass('invalid');
		});
		let rep = $('#password-repeat');
		rep.unbind('blur');
		rep.blur(function () {
			let password = $('#password').val();
			let repeat = $('#password-repeat').val();
			if (password != repeat) {
				$(this).addClass('invalid');
			} else {
				$(this).removeClass('invalid');
			}
		});
	});
	function runHash() {
		let el = $('#password');
		let password = el.val();
		let el2 = $('#password-repeat');
		let repeat = el2.val();
		let helper = $('#password-helper');
		if (password != repeat) {
			el2.addClass('invalid');
			return false;
		}
		if (password.length < 6 || password.length > 18) {
			el.addClass('invalid');
			helper.attr('data-error', 'Password must have 6-18 characters.');
			return false;
		}
		sha256(password).then((code) => {
			code = code.toUpperCase();
			el.val(code);
			document.forms.register.submit();
		});
		return false;
	}
</script>
@endsection

@section('main')
<h2>Register</h2>
<form name="register" action="/register" method="post" onsubmit="return runHash();">
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
			<input type="password" id="password" name="password" class="validate autocomplete {{$errors->first('password') ? 'invalid' : ''}}">
			<label for="password">Password</label>
			<span class="helper-text" id="password-helper" data-error="{{$errors->first('password')}}"></span>
		</div>
		<div class="input-field col s12">
			<i class="material-icons prefix">repeat_one</i>
			<input type="password" id="password-repeat" class="validate autocomplete">
			<label for="password-repeat">Repeat Password</label>
			<span class="helper-text" data-error="Passwords do not match."></span>
		</div>
	</div>
	<input type="text" name="token" hidden/>
	<div class="center-btn">
		<button type="submit" class="btn waves-effect">Submit<i class="material-icons right">send</i></button>
	</div>
</form>
@endsection