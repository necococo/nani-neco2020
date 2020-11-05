@extends('layouts.app')

@section('content')

  @foreach($images as $image)
      @if($image->image_path)
          <!-- 画像を表示 -->
          <a href="{{ route('images.show', ['id'=>$image->id]) }}"><img src="{{ $image->image_path }}" style="width: auto; height: 100px;"/></a>
      @endif
  @endforeach
  
@endsection