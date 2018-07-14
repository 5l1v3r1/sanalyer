<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Folklore\Image\Facades\Image;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $collection = "users";
    protected $fillable = ['name', 'email', 'password', 'firstname', 'lastname', 'biography',];
    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->firstname . " " . $this->lastname;
    }

    public function profileUrl(){
        $url = route('show_profile',$this->name.'-'.$this->id);
        return $url;
    }

    public function profilePhoto()
    {
        $homeUrl = env('APP_URL');
        $image = Image::url(asset($this->photo ? '/rk_content/images/user-profile/' . $this->photo : '/rk_content/images/noavatar.png'), 72, 72, array('crop'));
        return $homeUrl . $image;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];
}
