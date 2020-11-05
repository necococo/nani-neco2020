@extends('layouts.app')
@section('content')
  <form action="{{ action('ImagesController@store') }}" method="post" enctype="multipart/form-data">
    <!-- アップロードフォームの作成 -->
    <input type="file" name="file">
    {{ csrf_field() }}
    <input class="btn btn-primary" type="submit" value="Upload">
  </form>
@endsection
