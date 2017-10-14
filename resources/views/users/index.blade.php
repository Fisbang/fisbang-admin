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

	<h1>All the Users</h1>

	<!-- will be used to show any messages -->
	@if (Session::has('message'))
		<div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>@sortablelink('email', 'Email')</td>
				<td>@sortablelink('name', 'Name')</td>
				<td>Role</td>
				<td>Avatar</td>
				<td>Actions</td>
			</tr>
		</thead>
		<tbody>
		@foreach($users as $key => $value)
			<tr>
				<td>{{ $value->email }}</td>
				<td>{{ $value->name }}</td>
				<td>{{ $value->role()->first()->role_name }}</td>
				@if ($value->avatar != null)
					<td><img src="{{ $value->avatar }}"/></td>
				@else
					<td><img src="{{ $value->getGravatarUrl() }}"/></td>
				@endif

				<!-- we will also add show, edit, and delete buttons -->
				<td>

					<!-- delete the user (uses the destroy method DESTROY /users/{id} -->
					{{ Form::open(array('url' => 'users/' . $value->id, 'class' => 'pull-right')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
					{{ Form::close() }}

					<!-- show the nerd (uses the show method found at GET /users/{id} -->
					<a class="btn btn-small btn-success" href="{{ URL::to('users/' . $value->id) }}">Show</a>

					<!-- edit this nerd (uses the edit method found at GET /users/{id}/edit -->
					<a class="btn btn-small btn-info" href="{{ URL::to('users/' . $value->id . '/edit') }}">Edit</a>

				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	
	{!! $users->appends(\Request::except('page'))->render() !!}
</div>
@endsection
