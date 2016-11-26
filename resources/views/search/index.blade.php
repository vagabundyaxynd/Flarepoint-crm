@extends('layouts.master')

@section('content')

@foreach($results as $result)
	{{ $result->title }} <br />
@endforeach
{{ $results->links() }}
@endsection