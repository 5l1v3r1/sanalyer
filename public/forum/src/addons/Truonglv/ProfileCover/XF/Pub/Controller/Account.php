<?php
/**
 * @license
 * Copyright 2017 TruongLuu. All Rights Reserved.
 */
namespace Truonglv\ProfileCover\XF\Pub\Controller;

class Account extends XFCP_Account
{
    public function actionCover()
    {
        $this->assertUploadCover();

        return $this->addAccountWrapperParams(
            $this->view('XF:Account\Cover', 'tl_profile_cover_upload'),
            'cover'
        );
    }

    public function actionCoverSave()
    {
        $this->assertUploadCover();
        $cover = $this->getCoverService();

        $coverImg = $this->request->getFile('cover', false, false);
        if (!$coverImg) {
            return $this->error(\XF::phrase('uploaded_file_must_be_valid_image'));
        }

        $cover->setImage($coverImg->getTempFile());

        if ($this->filter('reposition', 'bool')) {
            if (!$cover->canRepositionCover()) {
                return $this->noPermission();
            }

            $cover->saveReposition();
        } else {
            if (!$cover->upload($error)) {
                return $this->error($error);
            }
        }

        return $this->redirect($this->buildLink('members', \XF::visitor()));
    }

    protected function assertUploadCover()
    {
        if (!$this->getCoverService()->canUploadCover($error)) {
            throw $this->errorException($error);
        }
    }

    /**
     * @return \Truonglv\ProfileCover\Service\Cover
     */
    protected function getCoverService()
    {
        return $this->service('Truonglv\ProfileCover:Cover', \XF::visitor());
    }
}