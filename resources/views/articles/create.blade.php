@extends('layouts.app')

@section('content')
<div class="container">
    <nav class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ URL::to('articles') }}">Articles</a>
		</div>
		<ul class="nav navbar-nav">
			<li><a href="{{ URL::to('articles') }}">View All Articles</a></li>
			<li><a href="{{ URL::to('articles/create') }}">Create an Article</a>
		</ul>
	</nav>

	<h1>Create an Article</h1>

	<!-- if there are creation errors, they will show here -->
	{{ Html::ul($errors->all()) }}

	{{ Form::open(array('url' => 'articles', 'files' => true)) }}

		<div class="form-group">
			{{ Form::label('title', 'Title') }}
			{{ Form::text('title', Input::old('title'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('description', 'Description') }}
			{{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('url', 'Url') }}
			{{ Form::text('url', Input::old('url'), array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('image', 'Image') }}
			{{ Form::file('image', array('class' => 'form-control')) }}
		</div>

		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
</div>
@endsection
