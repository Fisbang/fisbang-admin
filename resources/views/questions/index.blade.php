@extends('layouts.app')

@section('content')
<div class="container">
    <nav class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ URL::to('questions') }}">Questions</a>
		</div>
		<ul class="nav navbar-nav">
			<li><a href="{{ URL::to('questions') }}">View All Questions</a></li>
			<li><a href="{{ URL::to('questions/create') }}">Create an Question</a>
		</ul>
	</nav>

	<h1>All the Questions</h1>

	<!-- will be used to show any messages -->
	@if (Session::has('message'))
		<div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>@sortablelink('owner_id', 'Owner ID')</td>
				<td>User</td>
				<td>Question</td>
				<td>Image</td>
				<td>@sortablelink('is_premium', 'Question Type')</td>
				<td>@sortablelink('hours', 'Hours')</td>
				<td>@sortablelink('billing', 'Billing')</td>
				<td>@sortablelink('is_answered', 'Status')</td>
				<td>Actions</td>
			</tr>
		</thead>
		<tbody>
		@foreach($questions as $key => $value)
			<tr>
				<td>{{ $value->user()->first()->id }}</td>
				<td>{{ $value->user()->first()->email }}</td>
				<td>{{ $value->messages()->first()->content }}</td>
				@if ($value->messages()->first()->attachments()->first() != null)
					<td><a target="_blank" href="{{ $value->messages()->first()->attachments()->first()->path }}">show</a></td>
				@else 
					<td></td>
				@endif
				@if ($value->is_premium)
					<td>Premium</td>
				@else 
					<td>Standard</td>
				@endif
				<td>{{ $value->hours }}</td>
				<td>{{ $value->billing }}</td>
				@if ($value->is_answered)
					<td>Closed</td>
				@else 
					<td>Unanswered</td>
				@endif

				<!-- we will also add show, edit, and delete buttons -->
				<td>

					<!-- delete the question (uses the destroy method DESTROY /questions/{id} -->
					{{ Form::open(array('url' => 'questions/' . $value->id, 'class' => 'pull-right')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
					{{ Form::close() }}

					<!-- show the nerd (uses the show method found at GET /questions/{id} -->
					<a class="btn btn-small btn-success" href="{{ URL::to('questions/' . $value->id) }}">Show</a>

					<!-- edit this nerd (uses the edit method found at GET /questions/{id}/edit -->
					<a class="btn btn-small btn-info" href="{{ URL::to('questions/' . $value->id . '/edit') }}">Edit</a>

				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{!! $questions->appends(\Request::except('page'))->render() !!}
</div>
@endsection
