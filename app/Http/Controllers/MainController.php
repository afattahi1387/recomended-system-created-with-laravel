<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Requests\AddAndEditVideoRequest;
use App\RecomendedSystem\RecomendedSystem;

class MainController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function home() {
        $obj = new RecomendedSystem(auth()->user()->id);
        if(auth()->user()->type == 'user') {
            return redirect()->route('users.dashboard');
        } elseif(auth()->user()->type == 'admin') {

            if(isset($_GET['edit-genre']) && !empty($_GET['edit-genre'])) {
                $edit_genre = Genre::find($_GET['edit-genre']);
            } else {
                $edit_genre = null;
            }

            if(isset($_GET['edit-video']) && !empty($_GET['edit-video'])) {
                $edit_video = Video::find($_GET['edit-video']);
            } else {
                $edit_video = null;
            }

            return view('admins_dashboard.admins_dashboard', ['all_genres' => Genre::all(), 'genres_array' => $obj->get_genres_name(), 'videos_array' => $obj->get_all_videos(), 'edit_genre' => $edit_genre, 'edit_video' => $edit_video]);
        }
    }

    public function insert_genre(Request $request) {
        Genre::insert([
            'name' => $request->genre_name
        ]);

        return redirect()->route('home');
    }

    public function update_genre(Request $request, Genre $genre) {
        $genre->update([
            'name' => $request->genre_name
        ]);

        return redirect()->route('home');
    }

    public function insert_video(AddAndEditVideoRequest $request) {
        $name = $request->name;
        $genre = $request->genre;

        Video::create([
            'name' => $name,
            'genres' => $genre
        ]);

        return redirect()->route('home');
    }

    public function update_video(AddAndEditVideoRequest $request, Video $video) {
        $name = $request->name;
        $genre = $request->genre;

        $video->update([
            'name' => $name,
            'genres' => $genre
        ]);

        return redirect()->route('home');
    }

    public function delete_record($type, $id) {
        if($type == 'genre') {
            $record = Genre::find($id);
        } elseif($type == 'video') {
            $record = Video::find($id);
        }
        
        $record->delete();
        return redirect()->route('home');
    }
}
