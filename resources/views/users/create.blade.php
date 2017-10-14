@extends('layouts.app')

@section('content')
<div class="container">
    <nav class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ URL::to('users') }}">Users</a>
		</div>
		<ul class="nav navbar-nav">
			<li><a href="{{ URL::to('users') }}">View All Users</a></li>
			<li><a href="{{ URL::to('users/create') }}">Create an User</a>
		</ul>
	</nav>

	<h1>Create an User</h1>

	<!-- if there are creation errors, they will show here -->
	{{ Html::ul($errors->all()) }}

	{{ Form::open(array('url' => 'users')) }}

		<div class="form-group">
			{{ Form::label('name', 'Name') }}
			{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password', Input::old('password'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('role_id', 'Role') }}
			{{ Form::select('role_id', $roles, Input::old('role_id'), array('class' => 'form-control')) }}
		</div>

		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
</div>
@endsection
