<?php

namespace Radkod\Xenforo2\XenforoBridge;

use Radkod\Xenforo2\XenforoBridge\Contracts\Factory as FactoryContract;

class XenforoBridge implements FactoryContract
{
    private $csrf;
    private $user;
    private $logoutUrl;
    private $directory_path;

    /**
     * XenforoBridge constructor.
     */
    public function __construct()
    {
        $this->directory_path = env('FORUM_DIR');
        try {
            $this->loadXF();
        } catch (\Exception $e) {
        }
    }

    /**
     * @return string
     */
    public function csrf()
    {
        return $this->csrf;
    }

    /**
     * @return array
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->user()->user_id;
    }

    /**
     * @param string $size
     * @return string
     */
    public function photo($size = 'l')
    {
        // size : xxs, xs, s, m, l, o
        $group = floor($this->id() / 1000);
        if ($this->user->avatar_date) {
            return env('FORUM_URL') . "/data" . "/avatars/$size/$group/" . $this->id() . ".jpg?" . $this->user()->avatar_date;
        } else {
            return asset('/rk_content/images/noavatar.png');
        }
    }

    /**
     * @return string
     */
    public function logout()
    {
        return $this->logoutUrl;
    }

    /**
     * @return bool
     */
    public function check()
    {
        return ($this->id() > 0 ? true : false);
    }

    /**
     * @throws \Exception
     */
    protected function loadXF()
    {
        $path = $this->directory_path . '/src/XF.php';
        if (file_exists($path) && is_readable($path) && require_once($path)) {
            \XF::start('/hc');
            $app = \XF::setupApp('XF\Pub\App');
            $this->user = \XF::finder('XF:User')->where('user_id', $app->session()->get('userId'))->fetchOne();

            $this->csrf = htmlspecialchars($app['csrf.token']);
            $this->logoutUrl = $app->router()->buildLink( 'canonical:logout', env('APP_URL'), array(
                't' => $this->csrf,
                'redirect'=>env('APP_URL')
            ) );
        } else {
            throw new \Exception('Could not load XenForo_Autoloader.php check path: ' . $path);
        }
    }
}