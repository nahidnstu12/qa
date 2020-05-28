<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;

class QuestionsController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth',['except' => ['index','show']]);
    }
    
    public function index()
    { 
        $questions = Question::with('user')->latest()->paginate(8);
        return view('questions.index',compact('questions'));
    }

     
    public function create()
    {
        //
        $question = new Question();
        return view('questions.create',compact('question'));
    }

    public function store(AskQuestionRequest $request)
    {
        //
        $request->user()->questions()->create($request->only('title','body'));

        return redirect()->route('questions.index')->with('success',"your question has been submitted");
    }

   
    public function show(Question $question)
    {
        //
        $question->increment('views');
        return view('questions.show',compact('question'));
    }

   
    public function edit(Question $question)
    {
        //
        $this->authorize('update',$question);
        return view('questions.edit',compact('question'));
    }

   
    public function update(AskQuestionRequest $request, Question $question)
    {
        //
        $this->authorize('update',$question);
        $question->update($request->only('title','body'));
        if(\request()->expectsJson()){
            return \response()->json([
                'message' => "Your Question has been updated",
                'body_html' => $question->body_html
            ]);
        }
        return redirect('/questions')->with('success',"Your Question has been updated");

    }

    public function destroy(Question $question)
    {
        //
        $this->authorize('delete',$question);
        $question->delete();
        if(\request()->expectsJson()){
            return \response()->json([
                'message' => "Your Question has been deleted",
            ]);
        }
        return redirect('/questions')->with('success',"Your question has been deleted");
    }
}
