@extends('layouts.app')
@section('content')

{!! Form::open(['route' => 'images.store', 'files' => true ]) !!}
    {!! Form::file('file') !!}
    {!! Form::submit('Upload', ['class' => 'btn btn-success']) !!}
{!! Form::close() !!}

@endsection




  <!--<form action="{{ action('ImagesController@store') }}" method="post" enctype="multipart/form-data">-->
  <!--  <input type="file" name="file">-->
  <!--  {{ csrf_field() }}-->
  <!--  <input class="btn btn-primary" type="submit" value="Upload">-->
  <!--</form>-->




