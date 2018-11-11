<?php

namespace Radkod\Xenforo2\XenforoBridge;

class XenforoBridge {

    private $user;
    private $directory_path;
    private $url;

    /*public function __construct($directory_path, $url)
    {
        $this->directory_path = $directory_path;
        $this->url = $url;
        $this->loadXF();
    }*/

    public function __construct()
    {
        $this->directory_path = '/home/sanalyer/public_html/forum';
        $this->url = 'sanalyer.com/forum';
        $this->loadXF();
    }

    public function user()
    {
        return $this->user;
    }

    public function id()
    {
        return $this->user()->user_id;
    }

    public function check() {
        return ($this->id() > 0 ? true : false);
    }

    protected function loadXF()
    {
        $path = $this->directory_path . '/src/XF.php';
        if( file_exists($path) && is_readable($path) && require_once($path)) {
            \XF::start('/hc');
            $app = \XF::setupApp('XF\Pub\App');
            $this->user = \XF::finder('XF:User')->where('user_id', $app->session()->get('userId'))->fetchOne();
        } else {
            throw new \Exception('Could not load XenForo_Autoloader.php check path: ' . $path);
        }
    }
}