<?php
namespace Radkod\Posts\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null user_id
 * @property int status
 * @property int parent_id
 * @property int posts_id
 * @property string content
 */
class Comments extends Model{
    protected $table = "comments";

    protected $hidden = ['content'];


    public function children() {
        return $this->hasMany('Radkod\Posts\Models\Comments', 'parent_id', 'id');
    }

    public function user(){
        return $this->belongsTo("App\Forum\User","user_id", 'user_id');
    }

    public function posts(){
        return $this->belongsTo('Radkod\Posts\Models\Posts','posts_id', 'id');
    }
}