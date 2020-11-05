<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Storage;

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
                'mimes:jpeg,png',
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
            
            $image->save();
            
            return redirect('/');
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
