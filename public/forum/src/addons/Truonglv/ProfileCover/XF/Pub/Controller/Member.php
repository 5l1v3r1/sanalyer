<?php
/**
 * @license
 * Copyright 2017 TruongLuu. All Rights Reserved.
 */
namespace Truonglv\ProfileCover\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;

class Member extends XFCP_Member
{
    public function actionView(ParameterBag $params)
    {
        $response = parent::actionView($params);
        if ($response instanceof View) {
            /** @var \Truonglv\ProfileCover\Service\Cover $cover */
            $cover = $this->service('Truonglv\ProfileCover:Cover', $response->getParam('user'));

            $viewMode = 'normal';
            if ($this->filter('reposition', 'bool')
                && $cover->canRepositionCover()
            ) {
                $viewMode = 'reposition';
            }

            $response->setParam('profileCover_viewMode', $viewMode);

            $response->setParam('profileCover_canUpload', $cover->canUploadCover());
            $response->setParam('profileCover_canDelete', $cover->canDeleteCover());
            $response->setParam('profileCover_canReposition', $cover->canRepositionCover());
        }

        return $response;
    }

    public function actionCoverDelete(ParameterBag $params)
    {
        $this->assertValidCsrfToken($this->filter('t', 'str'));
        $user = $this->assertViewableUser($params->user_id);

        /** @var \Truonglv\ProfileCover\Service\Cover $cover */
        $cover = $this->service('Truonglv\ProfileCover:Cover', $user);
        if (!$cover->canDeleteCover()) {
            return $this->noPermission();
        }

        $cover->delete();

        return $this->redirect($this->buildLink('members', $user));
    }
}