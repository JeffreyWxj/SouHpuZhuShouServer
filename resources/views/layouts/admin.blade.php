@extends('layouts.admin-frame')
@section('body')
  <div class="app-container">
    <div class="row content-container">
      <nav class="navbar navbar-default navbar-fixed-top navbar-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-expand-toggle">
              <i class="fa fa-bars icon"></i>
            </button>
            <ol class="breadcrumb navbar-breadcrumb">
              <li class="active">Dashboard</li>
            </ol>
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
              <i class="fa fa-th icon"></i>
            </button>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
              <i class="fa fa-times icon"></i>
            </button>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <i class="fa fa-comments-o"></i>
              </a>
              <ul class="dropdown-menu animated fadeInDown">
                <li class="title">
                  Notification
                  <span class="badge pull-right">0</span>
                </li>
                <li class="message">
                  No new notification
                </li>
              </ul>
            </li>
            <li class="dropdown danger">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <i class="fa fa-star-half-o"></i>
                4
              </a>
              <ul class="dropdown-menu danger  animated fadeInDown">
                <li class="title">
                  Notification
                  <span class="badge pull-right">4</span>
                </li>
                <li>
                  <ul class="list-group notifications">
                    <a href="#">
                      <li class="list-group-item">
                        <span class="badge">1</span>
                        <i class="fa fa-exclamation-circle icon"></i>
                        new registration
                      </li>
                    </a>
                    <a href="#">
                      <li class="list-group-item">
                        <span class="badge success">1</span>
                        <i class="fa fa-check icon"></i>
                        new orders
                      </li>
                    </a>
                    <a href="#">
                      <li class="list-group-item">
                        <span class="badge danger">2</span>
                        <i class="fa fa-comments icon"></i>
                        customers messages
                      </li>
                    </a>
                    <a href="#">
                      <li class="list-group-item message">
                        view all
                      </li>
                    </a>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="dropdown profile">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{Auth::user()->name}}
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu animated fadeInDown">
                <li class="profile-img">
                  <img src="{{config('static.admin')}}/img/profile/picjumbo.com_HNCK4153_resize.jpg" class="profile-img">
                </li>
                <li>
                  <div class="profile-info">
                    <h4 class="username">Emily Hart</h4>
                    <p>emily_hart@email.com</p>
                    <div class="btn-group margin-bottom-2x" role="group">
                      <button type="button" class="btn btn-default">
                        <i class="fa fa-user"></i>
                        Profile
                      </button>
                      <button type="button" class="btn btn-default">
                        <i class="fa fa-sign-out"></i>
                        Logout
                      </button>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
      <div class="side-menu sidebar-inverse">
        <nav class="navbar navbar-default" role="navigation">
          <div class="side-menu-container">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">
                <div class="icon fa fa-paper-plane"></div>
                <div class="title">Flat Admin V.2</div>
              </a>
              <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                <i class="fa fa-times icon"></i>
              </button>
            </div>
            <ul class="nav navbar-nav">
              <li>
                <a href="{{route('admin.index.index')}}">
                  <span class="icon fa fa-tachometer"></span>
                  <span class="title">控制面板</span>
                </a>
              </li>
              <!-- Dropdown-->
              <li class="panel panel-default dropdown">
                <a data-toggle="collapse" href="#dropdown-system-setting">
                  <span class="icon fa fa-cogs"></span>
                  <span class="title">系统设置</span>
                </a>
                <!-- Dropdown level 1 -->
                <div id="dropdown-system-setting" class="panel-collapse collapse">
                  <div class="panel-body">
                    <ul class="nav navbar-nav">
                      <li>
                        <a href="{{route('admin.system.env-setting')}}">环境变量</a>
                      </li>
                      <li>
                        <a href="icons/font-awesome.html">Font Awesomes</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <!-- /.navbar-collapse -->
        </nav>
      </div>
      <div class="copyrights">Collect from
        <a href="http://www.cssmoban.com/">免费网站模板</a>
      </div>
      <!-- Main Content -->
      <div class="container-fluid">
        <div class="side-body padding-top">
          @yield('content')
        </div>
      </div>
    </div>
    <footer class="app-footer">
      <div class="wrapper">
        <span class="pull-right">2.1 <a href="#"><i class="fa fa-long-arrow-up"></i></a></span>
        © 2017 SouHPU Copyright.
      </div>
    </footer>
    <div>
    </div>
  </div>
@endsection