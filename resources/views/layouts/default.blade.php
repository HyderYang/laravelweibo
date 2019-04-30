<!doctype html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<title>@yield('title') - Hyder</title>
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	
</head>

<body>
	@include('layouts._header')
	
	<div class="container">
	  <div class="offset-md-1 col-md-10">
		  @include('shared._messages')
		  
		  @yield('content')
		  
		  @include('layouts._footer')
	  </div>
	</div>
	
	<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

