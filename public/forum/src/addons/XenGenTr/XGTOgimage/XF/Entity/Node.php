<?php

namespace XenGenTr\XGTOgimage\XF\Entity;

use XF\Mvc\Entity\Structure;

class Node extends XFCP_Node
{
    public function getXgtOgImageYol($xgt_ogdb, $mode = 'node')
    {
        if(empty($xgt_ogdb))
            return false;

        if(empty($xgt_ogdb['xgtog_baglanti']) && empty($xgt_ogdb['xgtOg_yukle']))
            return false;

        if(!empty($xgt_ogdb['xgtog_baglanti']))
        {
            return $xgt_ogdb['xgtog_baglanti'];
        }

        if(!empty($xgt_ogdb['xgtOg_yukle']))
        {
            switch ($mode)
            {

                case 'thread':
                    return sprintf('data://XenGenTr/xgt_og_images/%s', $xgt_ogdb['xgtOg_yukle']);
                    break;
            }
        }

        return false;
    }

    public function getXgtOgImageBaglanti($xgt_ogdb, $mode = 'node')
    {
        $app = $this->app();

        if(empty($xgt_ogdb))
            return false;


        if(empty($xgt_ogdb['xgtog_baglanti']) && empty($xgt_ogdb['xgtOg_yukle']))
            return false;

        if(!empty($xgt_ogdb['xgtog_baglanti']))
        {
            return $xgt_ogdb['xgtog_baglanti'];
        }

        if(!empty($xgt_ogdb['xgtOg_yukle']))
        {
            switch ($mode)
            {
                case 'thread':
                    return $app->applyExternalDataUrl(sprintf('XenGenTr/xgt_og_images/%s', $xgt_ogdb['xgtOg_yukle']), true);
                    break;
            }
        }

        return false;
    }
}
