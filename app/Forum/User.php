<?php

namespace App\Forum;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int avatar_date
 */
class User extends Model
{
    protected $primaryKey = 'user_id';
    protected $connection = 'forum_mysql';
    protected $table = 'user';
    protected $appends = ['fullname', 'pp', 'firstname', 'photo'];

    public function getFullnameAttribute(){
        return $this->username;
    }

    public function getPhotoAttribute(){
        return $this->userPp();
    }

    public function getFirstnameAttribute(){
        return $this->username;
    }

    public function userPp($size = null){
        if($this->avatar_date){
            $group = floor($this->user_id / 1000);
            if($size == null){
                return env('FORUM_URL'). "/data" . "/avatars/l/$group/$this->user_id.jpg?$this->avatar_date";
            }else{
                $size = explode(',', $size);
                return env('FORUM_URL'). "/data" . "/avatars/$size/$group/$this->user_id.jpg?$this->avatar_date";
            }
        }else{
            return asset('/rk_content/images/noavatar.png');
        }
    }

    public function profileUrl(){
        $url = route('show_profile',$this->username.'-'.$this->user_id);
        return $url;
    }

    /**
     * @param string $size
     * @return string
     */
    public function profilePhoto($size='l')
    {
        if($this->avatar_date){
            $group = floor($this->user_id / 1000);
            return env('FORUM_URL'). "/data" . "/avatars/$size/$group/$this->user_id.jpg?$this->avatar_date";
        }else{
            return asset('/rk_content/images/noavatar.png');
        }
    }

}
