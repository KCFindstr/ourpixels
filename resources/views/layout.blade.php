<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title>@yield('title')</title>
	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"/>
	<!-- Compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- my css -->
	<link rel="stylesheet" href="{{ URL::asset('css/common.css') }}"/>
	<script>
	async function sha256(message) {
		const msgBuffer = new TextEncoder('utf-8').encode(message);										
		const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
		const hashArray = Array.from(new Uint8Array(hashBuffer));
		const hashHex = hashArray.map(b => ('00' + b.toString(16)).slice(-2)).join('');
		return hashHex;
	}
	$(document).ready(() => {
		$('input').blur(function () {
			$(this).removeClass('invalid');
		});
		$('select').formSelect();
	});
	</script>
	@yield('head')
</head>
<body>
	<form name="logout" action="/logout" method="post" hidden>@csrf</form>
	<nav>
		<div class="nav-wrapper">
			<a href="/" class="brand-logo waves-effect" id="logo">OurPixels</a>
			<ul class="right">
				@if (!Auth::check())
				<li><a href="/signin" class="waves-effect">Sign in</a></li>
				<li><a href="/register" class="waves-effect">Register</a></li>
				@else
				<li><a class="waves-effect" href="/user/{{ Auth::user()->UserId }}">{{ Auth::user()->Username }}</a></li>
				<li><a class="waves-effect" href="#" onclick="document.forms.logout.submit();">Logout</a></li>
				@endif
			</ul>
		</div>
	</nav>

	<main class="container">
		@yield('main')
	</main>

	<footer class="page-footer">
		<div class="container">
			<div class="row">
				OurPixels &copy; <a href="mailto:changyuz@usc.edu" style="color: white;">Changyu Zhu</a> 2019
			</div>
		</div>
	</footer>
</body>
</html>