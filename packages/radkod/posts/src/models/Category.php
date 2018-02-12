<?php
namespace Radkod\Posts\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Category extends Model{
    protected $collection = "categories";
    protected $hidden = ['content','tag','design',"created_at","updated_at"];
    protected $fillable = ['title','id'];


    public function parent() {
        return $this->hasMany('Radkod\Posts\Models\Category', 'id', 'parent_id');
    }

    public function posts(){
        return $this->hasMany("Radkod\Posts\Models\Posts","id",'category');
    }
}