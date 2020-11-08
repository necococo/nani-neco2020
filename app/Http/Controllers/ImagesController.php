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
                //表示速度の問題で2MB以下 
                //そのままだとphp.iniに書かれた制限に先に引っかかるのでなにもエラー表示してくれないのでphp.iniのupload_max_filesize = 2M　->　5.1Mにした。
                'max:2048'
            ]
        ]);
        
        if( $request->file('file')->isValid([]) ) {
            $image = new Image;
            // バケットの`inputs`フォルダへアップロード
            $path = Storage::disk('s3')->putFile('inputs', $request->file('file'), 'public');
            // アップロードした画像のフルパスを取得
            $image->image_path = Storage::disk('s3')->url($path);
            
            $USERNAME = env('whatcat_username');
            $PASSWORD = env('whatcat_password');
            
            $header = ['Content-Type:multipart/form-data'];
            $cfile = array('image' => new \CURLFile( $_FILES["file"]["tmp_name"]) );
            // var_dump("cfile:".print_r($cfile,true));
            $api_url = 'http://whatcat.ap.mextractr.net/api_query';
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $cfile);
            curl_setopt($curl, CURLOPT_USERPWD, "$USERNAME:$PASSWORD");
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //TRUE を設定すると、curl_exec() の返り値を 文字列で返します。通常はデータを直接出力します。
            // $analized = curl_exec($curl);  
            $response = curl_exec($curl);
            $outputs = json_decode($response, true);
            // $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl); // curlの処理終わり
            
            // $result = [];
            // $result['http_code'] = $httpCode;
            // $result['body'] = $body;
            // return $result;
             
            $image->analized = $response;
            $image->save();
            
            return view('images.show', ['image' => $image, 'outputs'=>$outputs]);
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
