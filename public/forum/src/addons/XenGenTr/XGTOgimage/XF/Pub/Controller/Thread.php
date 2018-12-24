<?php

namespace XenGenTr\XGTOgimage\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
    public function actionIndex(ParameterBag $params)
    {
        $res = parent::actionIndex($params);

        $page = $params->page;

        if($res instanceof \XF\Mvc\Reply\View && !empty($res->getParam('thread')))
        {
            $thread = $res->getParam('thread');
            $forum = $res->getParam('forum');
            $posts = $res->getParam('posts');

            if(empty($posts) || empty($thread))
                return $res;

            if(empty($thread->xgt_ogdb))
                $thread->xgt_ogdb = [];

            $imageFound = false;

            $xgtOgKonum = '';

            // Goruntuleri sirala
            if(!empty($thread->xgt_ogdb['xgtog_baglanti']) || !empty($thread->xgt_ogdb['xgtOg_yukle']))
            {
                if(!empty($thread->xgt_ogdb['xgtog_baglanti']))
                {
                    $xgtOgKonum = $thread->xgt_ogdb['xgtog_baglanti'];
                }
                elseif(!empty($thread->xgt_ogdb['xgtOg_yukle']))
                {
                    $xgtOgKonum = \XF::app()->applyExternalDataUrl(sprintf('XenGenTr/xgt_og_images/%s', $thread->xgt_ogdb['xgtOg_yukle']), true);
                }
            }

            elseif(!empty(\XF::options()->xgt_VarsayilanOg['xgtog_baglanti']))
            {
                $xgtOgKonum = \XF::options()->xgt_VarsayilanOg['xgtog_baglanti'];
            }

            if($xgtOgKonum)
            {
                $thread->xgt_ogdb = array_merge($thread->xgt_ogdb,
                    [
                        'xgtog_yol' => $xgtOgKonum
                    ]
                );

                $imageFound = true;
            }

            if(!empty($OgImageAyarlari['ogimage_attachment_fallback']) && !$imageFound)
            {
                $firstPost = $thread->FirstPost;

                preg_match('#\[img\]([^\[\]\'"]+)\[\/img\]#is', $firstPost->message, $matches);

                if(!empty($matches[1]))
                {
                    $thread->xgt_ogdb = array_merge($thread->xgt_ogdb,
                        [
                            'xgtog_yol' => $matches[1]
                        ]
                    );

                    $res->setParam('thread', $thread);

                    $imageFound = true;
                }
            }

            // konu tablosuna yaz
            $xgt_ogdb = [];
            if(!empty($thread->xgt_ogdb['meta_description']))
                $xgt_ogdb['meta_description'] = \XenGenTr\XGTOgimage\Listener::getCustomFieldTitle($thread->xgt_ogdb['meta_description'], $thread);

            if(!empty($thread->xgt_ogdb['meta_title']))
                $xgt_ogdb['meta_title'] = \XenGenTr\XGTOgimage\Listener::getCustomFieldTitle($thread->xgt_ogdb['meta_title'], $thread);

            if($xgt_ogdb)
            {
                $xgt_ogdb = array_merge($thread->xgt_ogdb, $xgt_ogdb);
                $thread->set('xgt_ogdb', $xgt_ogdb);
                unset($xgt_ogdb);
            }

        }

        return $res;
    }

    protected function setupThreadEdit(\XF\Entity\Thread $thread)
    {
        $editor = parent::setupThreadEdit($thread);

        $nodeIds = \XF::options()->xgt_OgHaricForumlar;

        if(\XF::visitor()->canSetOgDuzenler())
        {
            $xgt_ogdb['xgtOg_yukle'] = '';
            $xgt_ogdb = $this->filter('xgt_ogdb', 'array-str');
            $thread = $editor->getThread();
            $node = $thread->Forum->Node;

            $deleteImage = $this->filter('xgtog_sil', 'bool');
            $OgImageMevcut = $this->filter('xgtog_mevcut', 'str');

            $newFileName = null;

            /** @var \XenGenTr\XGTOgimage\Service\OgImage $ogHizmetleri */
            $ogHizmetleri = $this->service('XenGenTr\XGTOgimage:OgImage', $node, $thread, 'thread');

            $upload = $this->request->getFile('xgtOg_yukle', false, false);

            if(!empty($thread->xgt_ogdb['xgtOg_yukle']))
                $xgt_ogdb['xgtOg_yukle'] = $thread->xgt_ogdb['xgtOg_yukle'];

            if ($upload)
            {
                $xgt_ogdb['xgtog_baglanti'] = '';
                if (!$ogHizmetleri->setImageFromUpload($upload))
                {
                    throw $this->exception($ogHizmetleri->getError());
                }

                if (!$newFileName = $ogHizmetleri->guncelleOgImage($OgImageMevcut))
                {
                    throw $this->exception($this->error(\XF::phrase('')));
                }
            }
            elseif ($deleteImage)
            {
                $ogHizmetleri->silOgImage($OgImageMevcut);
                $xgt_ogdb['xgtOg_yukle'] = '';
            }

            if(!$xgt_ogdb['xgtog_baglanti'])
            {
                if($newFileName !== null)
                {
                    $xgt_ogdb['xgtog_baglanti'] = '';
                    $xgt_ogdb['xgtOg_yukle'] = $newFileName;
                }
            }
            else
            {
                if(!empty($thread->xgt_ogdb['xgtOg_yukle']))
                {
                    $ogHizmetleri->silOgImage($thread->xgt_ogdb['xgtOg_yukle']);
                }

                $xgt_ogdb['xgtOg_yukle'] = '';
            }

            $thread['xgt_ogdb'] = $xgt_ogdb;

            if ($upload)
            {
                $ogHizmetleri->nakletOgImage($xgt_ogdb, $node);
            }
        }

        return $editor;
    }

}
