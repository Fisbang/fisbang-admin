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

	<h1>Create an Question</h1>

	<!-- if there are creation errors, they will show here -->
	{{ Html::ul($errors->all()) }}

	{{ Form::open(array('url' => 'questions', 'files' => true)) }}

		<div class="form-group">
			{{ Form::label('owner_id', 'Sender') }}
			{{ Form::select('owner_id', $users, Input::old('owner_id'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('content', 'Content') }}
			{{ Form::textarea('content', Input::old('content'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('attachment', 'Attachment') }}
			{{ Form::file('attachment', array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('is_answered', 'Closed') }}
			{{ Form::checkbox('is_answered', 1, Input::old('is_answered'), array('class' => 'form-control regular-checkbox chk-delete')) }}<span class="custom-checkbox info"></span>
		</div>

		<div class="form-group">
			{{ Form::label('is_premium', 'Premium') }}
			{{ Form::checkbox('is_premium', 1, Input::old('is_premium'), array('class' => 'form-control regular-checkbox chk-delete')) }}<span class="custom-checkbox info"></span>
		</div>

		<div class="form-group">
			{{ Form::label('billing', 'Billing') }}
			{{ Form::text('billing', Input::old('billing'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('hours', 'Hours') }}
			{{ Form::text('hours', Input::old('hours'), array('class' => 'form-control')) }}
		</div>

		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
</div>
@endsection
