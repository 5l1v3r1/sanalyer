<?php
namespace Radkod\Posts\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Comments extends Model{
    protected $collection = "comments";
    protected $hidden = ['content'];


    public function children() {
        return $this->hasMany('Radkod\Posts\Models\Comments', 'parent_id', 'id');
    }

    public function user(){
        return $this->belongsTo("App\User","user_id", 'id');
    }

    public function posts(){
        return $this->belongsTo('Radkod\Posts\Models\Category','posts_id','id');
    }
}