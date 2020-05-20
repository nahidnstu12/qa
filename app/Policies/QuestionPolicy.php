<?php

namespace App\Policies;

use App\User;
use App\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    
    public function view(User $user, Question $question)
    {
        //
    }

   
    public function create(User $user)
    {
        //
    }

   
    public function update(User $user, Question $question)
    {
        //
        return $user->id === $question->user_id;

    }

    
    public function delete(User $user, Question $question)
    {
        //
        return $user->id === $question->user_id && $question->answers_count <1;
    }

   
    public function restore(User $user, Question $question)
    {
        //
    }

    
    public function forceDelete(User $user, Question $question)
    {
        //
    }
}
