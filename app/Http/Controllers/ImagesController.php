<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Storage;
use URL;

class ImagesController extends Controller
{
    public function index()
    {
        $images = Image::all();
        return view('images.index', ['images' => $images]);
    }
    
    
    public function create()
    {
        $image = new Image;
        return view('images.create', ['image' => $image]);
    }
    
    
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => [
                // 必須
                'required',
                // アップロードされたファイルであること
                'file',
                // 画像ファイルであること
                'image',
                // MIMEタイプを指定
                'mimes:jpeg,png,jpg',
                //表示速度の問題で2MB以下にしたい。 
                //そのままだとphp.iniに書かれた2MB制限に先に引っかかるので、なにもエラー表示してくれない。php.iniのupload_max_filesize = 2Mを5Mにした。
                'max:2048'
            ]
        ]);
        
        if( $request->file('file')->isValid([]) ) {
            $image = new Image;
            // バケットの`inputs`フォルダへアップロード
            $path = Storage::disk('s3')->putFile('inputs', $request->file('file'), 'public');
            // アップロードした画像のフルパスを取得
            $image->image_path = Storage::disk('s3')->url($path);
            
            //外部API利用設定
            $USERNAME = env('whatcat_username');
            $PASSWORD = env('whatcat_password');
            $header = ['Content-Type:multipart/form-data'];
            $cfile = array('image' => new \CURLFile( $_FILES["file"]["tmp_name"]) );
            // var_dump("cfile:".print_r($cfile,true));
            $api_url = 'http://whatcat.ap.mextractr.net/api_query';
            //APIの説明のcurlコマンドと同じ内容にする
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $cfile);
            curl_setopt($curl, CURLOPT_USERPWD, "$USERNAME:$PASSWORD");
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//TRUE を設定すると、curl_exec() の返り値を 文字列で返します。通常はデータを直接出力します。
            $response = curl_exec($curl);
            // $outputs = json_decode($image->analized, true);これはview側で処理することにした。
            // $is_cat = $outputs[0][1] >= 0.3;
            // $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl); // curlの処理終わり
             
            $image->analized = $response;
            $image->save();
            
            return view('images.show', ['image' => $image]);
        }else {
            return  redirect('/images/create');
        }
    }
    
    
    public function show($id)
    {
        $image = $image = Image::find($id);
        
        return view('images.show', ['image' => $image]);
        
    }
    
    
    public function destroy($id)
    {
        $image = Image::find($id);
        $image->delete();
        
        return redirect('/');
    }
}
