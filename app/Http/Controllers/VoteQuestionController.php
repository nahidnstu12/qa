<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

class VoteQuestionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');

    }
    public function __invoke(Question $question)
    {
        $vote = (int) request()->vote;
        $votesCount= Auth()->user()->voteQuestion($question,$vote);
        if(\request()->expectsJson()){
            return \response()->json([
                'message'=> 'Thanks for feedback',
                'votesCount'=> $votesCount 
            ]);
        }
        return \back();
        // dd('vote');
    }
}
