<?php

namespace App\Http\Controllers;

use App\Vote;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Requests\AddVoteRequest;
use App\RecomendedSystem\RecomendedSystem;

class UsersDashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function dashboard() {
        $obj = new RecomendedSystem(auth()->user()->id);
        $labels = json_encode(array_keys($obj->set_scores_for_genres()));
        $data = json_encode(array_values($obj->set_scores_for_genres()));
        $videos = array();
        foreach($obj->sort_results() as $video_id => $value) {
            $videos[] = Video::find($video_id);
        }
        return view('users_dashboard.users_dashboard', ['didnt_view_videos' => $obj->user_didnt_view_videos(), 'labels' => $labels, 'data' => $data, 'recomended_videos' => $videos]);
    }

    public function add_vote(AddVoteRequest $request) {
        $video = $request->video;
        $vote = $request->vote;
        $user_id = auth()->user()->id;
        Vote::create([
            'user_id' => $user_id,
            'video_id' => $video,
            'score' => $vote
        ]);

        return redirect()->route('users.dashboard');
    }

    public function edit_votes() {
        $obj = new RecomendedSystem(auth()->user()->id);
        return view('users_dashboard.edit_votes', ['votes' => $obj->get_user_votes()]);
    }

    public function update_vote(Request $request, Vote $vote) {
        $vote->update([
            'score' => $request->new_vote
        ]);

        return redirect()->route('edit.votes');
    }

    public function delete_vote(Vote $vote) {
        $vote->delete();
        return redirect()->route('edit.votes');
    }
}
