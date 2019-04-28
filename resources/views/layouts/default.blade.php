<!doctype html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<title>@yield('title') - Hyder</title>
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="/">Weibo App</a>
      <ul class="navbar-nav justify-content-end">
        <li class="nav-item"><a class="nav-link" href="/help">帮助</a></li>
        <li class="nav-item"><a class="nav-link" href="#">登录</a></li>
      </ul>
    </div>
  </nav>
	
	<div class="content">
	  <div class="title m-b-md">
		  @yield('content')
	  </div>
	</div>
</body>
</html>

