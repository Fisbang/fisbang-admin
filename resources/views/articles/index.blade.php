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

	<h1>All the Articles</h1>

	<!-- will be used to show any messages -->
	@if (Session::has('message'))
		<div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>@sortablelink('id', 'ID')</td>
				<td>@sortablelink('title', 'Title')</td>
				<td>@sortablelink('description', 'Description')</td>
				<td>@sortablelink('url', 'URL')</td>
				<td>Image</td>
				<td>Actions</td>
			</tr>
		</thead>
		<tbody>
		@foreach($articles as $key => $value)
			<tr>
				<td>{{ $value->id }}</td>
				<td>{{ $value->title }}</td>
				<td>{{ $value->description }}</td>
				<td>{{ $value->url }}</td>
				<td>
				@isset($value->image)
					<a target="_blank" href="{{ $value->image }}">show</a>
				@endisset
				</td>

				<!-- we will also add show, edit, and delete buttons -->
				<td>

					<!-- delete the article (uses the destroy method DESTROY /articles/{id} -->
					{{ Form::open(array('url' => 'articles/' . $value->id, 'class' => 'pull-right')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
					{{ Form::close() }}

					<!-- show the nerd (uses the show method found at GET /articles/{id} -->
					<a class="btn btn-small btn-success" href="{{ URL::to('articles/' . $value->id) }}">Show</a>

					<!-- edit this nerd (uses the edit method found at GET /articles/{id}/edit -->
					<a class="btn btn-small btn-info" href="{{ URL::to('articles/' . $value->id . '/edit') }}">Edit</a>

				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{!! $articles->appends(\Request::except('page'))->render() !!}
</div>
@endsection
