<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $appends = ['url','avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function answers(){
        return $this->hasMany(Answer::class);

    }
   public function getAvatarAttribute(){
       $email = $this->email;
       $size = 32;

    //    return "public/images/profile.jpg".md5(\strtolower(\trim($email)))."?s=".$size;
       return "https://www.gravatar.com/avatar/".md5(\strtolower(\trim($email)))."?s=".$size;

   }
   public function getUrlAttribute(){
        return '#';
    }
    // Question Favourite Control
   public function favourites(){
       return $this->belongsToMany(Question::class,'favourites')->withTimestamps();
   }

    // Vote Question & Answer Control
   public function voteQuestions(){
       return $this->morphedByMany(Question::class,'votable')->withTimestamps();
   }
   public function voteAnswers(){
       return $this->morphedByMany(Answer::class,'votable')->withTimestamps();
   }
   public function voteQuestion(Question $question,$vote){
       $voteQuestions = $this->voteQuestions();
       return $this->_vote($voteQuestions,$question,$vote);
   }
    public function voteAnswer(Answer $answer,$vote){
       $voteAnswers = $this->voteAnswers();
     return $this-> _vote($voteAnswers,$answer,$vote);
       
   }

   private function _vote($relationship,$model,$vote){

        if($relationship->where('votable_id',$model->id)->exists()){
           $relationship->updateExistingPivot($model,['vote'=>$vote]);
       }
       else{
           $relationship->attach($model,['vote' =>$vote]);          
       }
       $model->load('votes');
       $downVotes =(int)$model->downVotes()->sum('vote');
       $upVotes =(int)$model->upVotes()->sum('vote');

    //    $model->votes_count = ($upVotes > abs($downVotes)) ? $upVotes :(($upVotes < abs($downVotes)) ?  $downVotes : 0);

       $model->votes_count = $upVotes +  $downVotes;
       $model->save();
       return $model->votes_count;
   }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
