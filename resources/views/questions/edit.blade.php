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

	<h1>Edit Question {{ $question->id }}</h1>

	<!-- if there are creation errors, they will show here -->
	{{ Html::ul($errors->all()) }}

	{{ Form::model($question, array('route' => array('questions.update', $question->id), 'method' => 'PUT')) }}

		<div class="form-group">
			{{ Form::label('is_answered', 'Closed') }}
			{{ Form::checkbox('is_answered', 1, $question->is_answered, array('class' => 'form-control regular-checkbox chk-delete')) }}<span class="custom-checkbox info"></span>
		</div>

		<div class="form-group">
			{{ Form::label('is_premium', 'Premium') }}
			{{ Form::checkbox('is_premium', 1, $question->is_premium, array('class' => 'form-control regular-checkbox chk-delete')) }}<span class="custom-checkbox info"></span>
		</div>

		<div class="form-group">
			{{ Form::label('billing', 'Billing') }}
			{{ Form::text('billing', null, array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('hours', 'Hours') }}
			{{ Form::text('hours', null, array('class' => 'form-control')) }}
		</div>

		{{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}

</div>
@endsection
