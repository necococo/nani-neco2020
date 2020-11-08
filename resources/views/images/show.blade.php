@extends('layouts.app')

@section('content')

  
  @if($image->image_path)
      <!-- 画像を表示 -->
      <img src="{{ $image->image_path }}" style="width: auto; height: 200px;"/>
      <br>
      <?php $outputs = json_decode($image->analized, true); ?>
      @foreach($outputs as $output)
          {{ $output[0] }}:{{ round($output[1], 3) }}  <br>
      @endforeach
      
      {!! Form::open(['route' => ['images.destroy', $image->id], 'method' => 'delete']) !!}
          {!! Form::submit('Delete', ['class' => "btn btn-danger"]) !!}
      {!! Form::close() !!}
  @endif
  
  
@endsection