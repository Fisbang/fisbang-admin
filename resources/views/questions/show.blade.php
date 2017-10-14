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

	<h1>Question {{ $question->id }}</h1><a class="btn btn-small btn-info" href="{{ URL::to('questions/' . $question->id . '/edit') }}">Edit</a>
	<p>
		<strong>Creator:</strong> {{ $question->user()->first()->name }} ({{ $question->user()->first()->email }})<br>
		<strong>Date:</strong> {{ $question->created_at }}<br>
		<strong>Status:</strong> 
			@if ($question->is_answered) 
				Closed
			@else
				Unanswered
			@endif
		<br>
		<strong>Type:</strong> 
			@if ($question->is_premium) 
				Premium
			@else
				Standard
			@endif
		<br>
		<strong>Billing:</strong> {{ $question->billing }}<br>
		<strong>Hours:</strong> {{ $question->hours }}<br>
	</p><br>
	@foreach($question->messages()->get() as $key => $value)
	<p>
		<strong>From:</strong> {{ $value->user()->first()->name }} ({{ $value->user()->first()->email }})<br>
		<strong>Date:</strong> {{ $value->created_at }}<br>
		<strong>Message:</strong> {{ $value->content }}<br>
		@foreach($value->attachments()->get() as $key2 => $value2)
			<strong>Picture:</strong> <img src="{{ $value2->path }}"/><br>
		@endforeach
	</p>
	@endforeach
	<br>
	<h1>Reply</h1>

	<!-- if there are creation errors, they will show here -->
	{{ Html::ul($errors->all()) }}

	{{ Form::open(array('url' => 'messages', 'files' => true)) }}

		<div class="form-group">
			{{ Form::hidden('sender_id', Auth::user()->id) }}
			{{ Form::hidden('question_id', $question->id) }}
		</div>

		<div class="form-group">
			{{ Form::label('content', 'Content') }}
			{{ Form::textarea('content', Input::old('content'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('attachment', 'Attachment') }}
			{{ Form::file('attachment', array('class' => 'form-control')) }}
		</div>

		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
</div>
@endsection
