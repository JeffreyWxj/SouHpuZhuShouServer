@extends('layouts.admin-frame')
@section('body')
	<div class="container">
		<div class="login-box">
			<div>
				<div class="login-form row">
					<div class="col-sm-12 text-center login-header">
						<i class="login-logo fa fa-connectdevelop fa-5x"></i>
						<h4 class="login-title">Flat Admin V2</h4>
					</div>
					<div class="col-sm-12">
						<div class="login-body">
							<div class="progress hidden" id="login-progress">
								<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
									Log In...
								</div>
							</div>
							<form action="{{ route('admin.login') }}" method="post" id="login-form">
								<div class="control">
									<input type="text" name="email" class="form-control" placeholder="请输入账户" />
								</div>
								<div class="control">
									<input type="password" name="password" class="form-control" placeholder="请输入密码" />
								</div>
								<div class="control">
									<input type="checkbox" name="remember"> 记住登录
								</div>
								<div class="login-button text-center">
									<input type="submit" class="btn btn-primary" value="Login">
								</div>
							</form>
						</div>
						<div class="login-footer">
							<span class="text-right"><a href="javascript:void(0)" class="color-white">Forgot password?</a></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection