<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }
    public function index(Question $question)
    {
        return $question->answers()->with('user')->simplePaginate(3);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question,Request $request)
    {
       $answer = $question->answers()->create($request->validate([
            'body' => 'required'
        ]) + ['user_id' => \Auth::id()]);

        if(\request()->expectsJson()){
            return \response()->json([
                'message' => "Your answer has been submitted",
                'answer' => $answer->load('user')
            ]);
        }

        return back()->with('success',"Your answer has been submitted");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question,Answer $answer)
    {
        //
        $this->authorize('update',$answer);
        return view('answers._edit',\compact('question','answer'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Question $question, Answer $answer)
    {
        //
        $this->authorize('update',$answer);
        $answer-> update($request->validate([
            'body' => 'required',
        ]));
        if(request()->expectsJson()){
            return response()->json([
                'message'=>'Your answer has been updated',
                'body_html'=> $answer->body_html
            ]);
        }
        // return \response()->json('update');

        // return \redirect()->route('questions.show',$question->slug)->with('success','Your answer is updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question,Answer $answer)
    {
        //
        $this->authorize('delete',$answer);
        $answer->delete();
        if(request()->expectsJson()){
            return response()->json([
                'message'=>'Your answer has been removed'                
            ]);
        }
        return \back()->with('success',"Your answer has been deleted");
    }
    public function getStatusAttribute(){
        return $this->id === $this->question->best_answer_id ? 'vote-accepted':'';
    }
}
