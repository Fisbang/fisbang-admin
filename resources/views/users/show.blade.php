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

	<h1>{{ $user->name }}</h1><a class="btn btn-small btn-info" href="{{ URL::to('users/' . $user->id . '/edit') }}">Edit</a><br><br>
	<p>
		<strong>Email:</strong> {{ $user->email }}<br>
		<strong>Role:</strong> {{ $user->role()->first()->role_name }}<br>
		@if ($user->avatar != null)
			<strong>Avatar:</strong> <img src="{{ $user->avatar }}"/>
		@else
			<strong>Avatar:</strong> <img src="{{ $user->getGravatarUrl() }}"/>
		@endif
	</p>
	<br>
	<h2>Appliances</h2>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>Name</td>
				<td>Type</td>
				<td>Brand</td>
				<td>Brand Type</td>
				<td>Power</td>
				<td>Building Name</td>
			</tr>
		</thead>
		<tbody>
		@foreach($user->buildings()->get() as $key => $value)
			@foreach($value->appliances()->get() as $key2 => $value2)
				<tr>
					<td>{{ $value2->name }}</td>
					<td>{{ $value2->type }}</td>
					<td>{{ $value2->brand }}</td>
					<td>{{ $value2->brand_type }}</td>
					<td>{{ $value2->power }}</td>
					<td>{{ $value->name }}</td>
				</tr>
			@endforeach
		@endforeach
		</tbody>
	</table>
</div>
@endsection
