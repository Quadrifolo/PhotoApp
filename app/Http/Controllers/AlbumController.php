<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Album;

class AlbumController extends Controller
{
    //

    public function index(){

        return view('album.index');
    }

    public function getAlbums(){

        $albums = Album::with('category')->where('user_id',auth()->user()->id)->get();
        
        return $albums;
    }


    public function create() { 

        return view('album.create');
    }

    public function store(Request $request){

        $this->validate($request,[

       

        'name'=>'required|min:3|max:15',
        'description'=>'required|min:3|max:300',
        'category_id'=>'required',
        'image'=>'required|mimes:jpeg,png,jpg'

        ]);
        // Get Hash For Image 
        $imageName = $request->image->hashName();
        
        // Moves Image to Public Directory 
        $request->image->move(public_path('album'),$imageName);
       // Create Method For Album
       $album= Album::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'category_id'=>$request->category_id,
            'slug'=>Str::slug($request->name),
            'user_id'=>auth()->user()->id,
            'image'=>$imageName



        ]);
        $id = $album->id;
        return response()->json(['id'=>$id]);
       }


        public function getOneAlbum($id){

            return Album::with('category')->find($id);

        }

        public function update($id,Request $request){

            $findAlbum = Album::find($id);
            $photo = $findAlbum->image;
            if($request->hasFile('image')){
                $file = $request->file('image');
                $photo = $file->hashName();
                $file->move('./album/',$photo);
            }

            $album = Album::find($id);
            $album->name = $request->name;
            $album->description = $request->description;
            $album->category_id = $request->category_id;
            $album->image = $photo;

            $success = $album->save();
            if($success){
                return response()->json($this->getAlbums());

            }

           

        }

        public function destroy($id){

            $album = Album::find($id)->delete();
            if($album){
                return response()->json($this->getAlbums());

            }
        }

    }


