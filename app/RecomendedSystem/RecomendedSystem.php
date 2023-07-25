<?php

namespace App\RecomendedSystem;

use App\Vote;
use App\Genre;
use App\Video;

class RecomendedSystem {

    public $user_id;

    public function __construct($user_id = 0) {
        $this->user_id = $user_id;
    }

    public function get_genres_name() {
        $genres_query = Genre::all();
        $genres_array = array();
        foreach($genres_query as $genre) {
            $genres_array[] = $genre['name'];
        }

        return $genres_array;
    }

    public function get_all_videos() {
        $videos_query = Video::all();
        $videos = array();
        foreach($videos_query as $video) {
            $videos[] = $video;
        }

        return $videos;
    }

    public function get_user_votes() {
        $votes_query = Vote::where('user_id', $this->user_id)->get();
        $votes = array();
        foreach($votes_query as $vote) {
            $votes[] = $vote;
        }

        return $votes;
    }

    public function user_viewed_videos() {
        $viewed_videos = array();
        foreach(self::get_user_votes($this->user_id) as $vote) {
            $viewed_videos[] = $vote['video_id'];
        }

        return $viewed_videos;
    }

    public function user_didnt_view_videos() {
        $didnt_view_videos = array();
        foreach(self::get_all_videos() as $video) {
            if(!in_array($video['id'], self::user_viewed_videos())) {
                $didnt_view_videos[] = $video;
            }
        }

        return $didnt_view_videos;
    }

    public function set_scores_for_user() {
        $user_votes = self::get_user_votes();
        $genres_name = self::get_genres_name();
        $scores = array();
        foreach($user_votes as $vote) {
            $video = Video::find($vote['video_id']);
            $video_genres = explode(', ', $video['genres']);
            $array = array();
            foreach($genres_name as $genre) {
                $array[$genre] = in_array($genre, $video_genres) ? 1 : 0;
            }
            $scores[$video['id'] . '===>' . $video['name']] = $array;
        }

        $new_scores = array();
        foreach($scores as $video_name => $score) {
            $video_scores = $score;
            foreach($score as $key => $sco) {
                $new_score = $sco == 1 ? Vote::where('user_id', $this->user_id)->where('video_id', substr($video_name, 0, strpos($video_name, '===>')))->get()[0]['score'] * $sco : 0;
                $video_scores[$key] = $new_score;
            }
            $new_scores[] = $video_scores;
        }

        return $new_scores;
    }

    public function set_scores_for_genres() {
        $user_scores = self::set_scores_for_user();
        $sum_of_scores = 0;
        $genres_scores = array();

        foreach(self::get_genres_name() as $genre_name) {
            $genres_scores[$genre_name] = 0;
        }

        foreach($user_scores as $score) {
            foreach($score as $genre => $genre_score) {
                $sum_of_scores += $genre_score;
                $genres_scores[$genre] += $genre_score;
            }
        }

        if($sum_of_scores == 0) {
            return array();
        }

        foreach($genres_scores as $key => $value) {
            $genres_scores[$key] = number_format($value / $sum_of_scores, 2);
        }

        return $genres_scores;
    }

    public function didnt_view_videos_scores() {
        $scores = array();
        foreach(self::user_didnt_view_videos() as $video) {
            $video_genres = explode(', ', $video['genres']);
            $array = array();
            foreach(self::get_genres_name() as $genre) {
                $array[$genre] = in_array($genre, $video_genres) ? 1 : 0;
            }
            $scores[$video['id'] . '===>' . $video['name']] = $array;
        }

        $new_scores = array();
        foreach($scores as $key => $value) {
            $newValue = $value;
            foreach($value as $genre_name => $genre_score) {
                if($genre_score == 1 && in_array($genre_name, self::set_scores_for_genres())) {
                    $newValue[$genre_name] = $genre_score * self::set_scores_for_genres()[$genre_name];
                }
            }
            $new_scores[$key] = $newValue;
        }

        return $new_scores;
    }

    public function calculate_some_of_DVV_scores() {
        $scores = array();
        foreach(self::didnt_view_videos_scores() as $video_name => $video_scores) {
            $sum = 0;
            foreach($video_scores as $genre_name => $genre_score) {
                $sum += $genre_score;
            }
            $scores[substr($video_name, 0, strpos($video_name, '===>'))] = $sum;
        }

        return $scores;
    }

    public function sort_results() {
        $results = self::calculate_some_of_DVV_scores();
        arsort($results);
        return $results;
    }
}
