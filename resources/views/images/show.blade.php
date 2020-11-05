@extends('layouts.app')

@section('content')

  
  @if($image->image_path)
      <!-- 画像を表示 -->
      <img src="{{ $image->image_path }}" style="width: auto; height: 200px;"/>
      {!! Form::open(['route' => ['images.destroy', $image->id], 'method' => 'delete']) !!}
          {!! Form::submit('Delete', ['class' => "btn btn-danger"]) !!}
      {!! Form::close() !!}
  @endif
  
  
@endsection