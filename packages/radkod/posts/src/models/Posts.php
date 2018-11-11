<?php
namespace Radkod\Posts\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model{
    protected $table = "posts";

    protected $hidden = ['content'];
    protected $appends = array('full_url');

    public function getUrl(){
        return str_slug($this->title."-".$this->id);
    }

    public function getFullUrlAttribute(){
        return $this->getUrl();
    }

    public function comments()
    {
        return $this->hasMany('Radkod\Posts\Models\Comments','id' ,'post_id');
    }

    public function user(){
        return $this->belongsTo("App\User","author", 'id');
    }

    public function category(){
        return $this->belongsTo('Radkod\Posts\Models\Category','category','id');
    }

    public function hitUpdate(){
        $id = $this->id;
        if(!isset($_COOKIE["post_".$id])) {
            $hitUpdate = Posts::find($id);
            $hitUpdate->views = $hitUpdate->views+1;
            $hitUpdate->save();
            setcookie("post_".$id, "_", time() + (86400 * 30), "/"); //
        }
    }


}