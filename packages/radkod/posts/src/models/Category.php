<?php
namespace Radkod\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Category extends Model{
    use Cachable;

    protected $table = "categories";

    protected $hidden = ['content', 'tag', 'design', "created_at", "updated_at"];
    protected $fillable = ['title','id'];
    protected $appends = array('full_url');

    public function getUrl(){
        return str_slug($this->title."-".$this->id);
    }

    public function getFullUrlAttribute(){
        return $this->getUrl();
    }

    public function parent() {
        return $this->hasMany('Radkod\Posts\Models\Category', 'id', 'parent_id');
    }

    public function posts(){
        return $this->hasMany("Radkod\Posts\Models\Posts","id",'category');
    }
}