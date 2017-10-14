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

	<h1>{{ $article->title }}</h1><a class="btn btn-small btn-info" href="{{ URL::to('articles/' . $article->id . '/edit') }}">Edit</a>
	<p>
		<strong>Description:</strong> {{ $article->description }}<br>
		<strong>Url:</strong> <a href="{{ $article->url }}">{{ $article->url }}</a><br>
		@isset($article->image)
			<strong>Picture:</strong> <img src="{{ $article->image }}"/><br>
		@endisset
	</p>
	<br/>
	
	<h2>Recipients</h2>
	<!-- will be used to show any messages -->
	@if (Session::has('message'))
		<div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
	<div>
		{{ Form::open(array('url' => 'articles/' . $article->id . '/add/', 'method' => 'POST')) }}
		{{ Form::hidden('article_id', $article->id) }}

		<div class="form-group">
			{{ Form::label('email', 'Add recipient by email') }}
			{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
		</div>
		
		{{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

		{{ Form::close() }}
	</div>
	<br/>
	<div>
		{{ Form::open(array('url' => 'articles/' . $article->id . '/addAll/', 'method' => 'POST')) }}
		{{ Form::hidden('article_id', $article->id) }}
		{{ Form::submit('Add All', array('class' => 'btn btn-info')) }}
		{{ Form::close() }}
	</div>
	<br/>
	<div>
		{{ Form::open(array('url' => 'articles/' . $article->id . '/removeAll/', 'class' => 'pull-right')) }}
		{{ Form::hidden('_method', 'DELETE') }}
		{{ Form::hidden('article_id', $article->id) }}
		{{ Form::submit('Delete All', array('class' => 'btn btn-warning')) }}
		{{ Form::close() }}
	</div>
	
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>Name</td>
				<td>Email</td>
				<td>Actions</td>
			</tr>
		</thead>
		<tbody>
		@foreach($article->users()->get() as $key => $value)
			<tr>
				<td>{{ $value->name }}</td>
				<td>{{ $value->email }}</td>

				<!-- we will also add show, edit, and delete buttons -->
				<td>
					<!-- delete the recipient (uses the destroy method DESTROY /articles/{id}/remove/{user_id} -->
					{{ Form::open(array('url' => 'articles/' . $article->id . '/remove/' . $value->id, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
					{{ Form::close() }}

				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
@endsection
