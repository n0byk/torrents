<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>{{$title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{$title}}">
    <meta name="author" content="{{$title}}">
	<!--==javascript==-->
    <script src="/templates/static/js/jquery.min.js"></script>
    <script src="/templates/static/js/bootstrap.js"></script>
    <!-- Le styles -->
    <link href="./templates/static/css/bootstrap.css" rel="stylesheet">
    <link href="./templates/static/css/tags.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="./templates/static/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head><body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="./">{{$site_name}}</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="./">{{$lang_home}}</a></li>
              <li><a href="addtorrent.php">{{$lang_addtorrent}}</a></li>
              <li><a href="#contact">{{$lang_contact}}</a></li>
            </ul>
            <ul class="nav pull-right">
    <li><a href="http://"><i class="icon-tags  icon-white"></i>RSS</a></li>
      </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>