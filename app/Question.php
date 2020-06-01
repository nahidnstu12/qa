<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Parsedown;

class Question extends Model
{
    // Vote Question Control
    use VotableTraites;

    protected $fillable = ['title','body'];
    protected $appends = ['created_date','favourites_count','is_favourited','body_html'];
        // one user,many questions
    public function user(){
        return $this->belongsTo(User::class);
    }
        // one question,many answers
    public function answers(){
        return $this->hasMany(Answer::class)->orderBy('votes_count','desc');
    }

    public function setTitleAttribute( $value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function getUrlAttribute(){
        return route("questions.show",$this->slug);
    }
    
    public function getCreatedDateAttribute(){
        return $this->created_at->diffForHumans();
    }
    public function getStatusAttribute(){
        if($this->answers_count >0){
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    // site scripting attack protection
    public function getBodyHtmlAttribute(){
        return $this->bodyHtml();
        // return clean($this->bodyHtml());
    }

    public function excerpt($len){
        return str_limit(strip_tags($this->bodyHtml()),$len);
    }
    private function bodyHtml(){
        return \Parsedown::instance()->text($this->body);
    }
    public function getExcereptAttribute(){
        return $this->excerpt(250);
     }

    //  Accept best answer control
    public function acceptBestAnswer(Answer $answer){
        $this->best_answer_id = $answer->id;
        $this->save();
    }
    // Favourited Question Control 
    public function favourites(){
       return $this->belongsToMany(User::class,'favourites')->withTimestamps();
   }
   public function isFavourited(){
        return $this->favourites()->where('user_id',auth()->id())->count()>0;
   }
   public function getIsFavouritedAttribute(){
        return $this->isFavourited();
   }
   public function getFavouritesCountAttribute(){
       return $this->favourites()->count();
   }
  
   
} 
