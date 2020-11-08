<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>nani-neco</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">

    </head>
    <body>
        
        @include('commons.navbar')

        <div class="container">
            <p>上のナビのUploadから2MB以下のjpgかpng画像をアップするニャン<br>
            イラストや人の顔でもできるニャン<br>
            スマホの場合は左上のシマシママークから見れくれニャン<br>
            AIがなんの猫にどのくらい似ているかを判定してくれるニャン<br>
            もう一度結果をみたいときはナビのAll Imagesからみたい画像を選択するニャン<br>
            現状では他の人の画像も削除できるので消さないであげてニャン<br>
            PUBG MOBILEとはなにも関係ないニャン</p>
            @include('commons.error_messages')

            @yield('content')
        </div>
    </body>
</html>
