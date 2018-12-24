<?php

namespace ThemeHouse\UIX\XF\Repository;

class Style extends XFCP_Style
{
    public function prepareTHStyles(Array $products)
    {
        $productIdMap = [];
        foreach ($products as $key=>&$product) {
            $productIdMap[$product['id']] = $key;

            $product['installed'] = false;
        }
        unset($product);

        $productIds = array_keys($productIdMap);

        $styles = $this->findStylesByTHProductIds($productIds);

        foreach ($styles as $style) {
            if (isset($productIdMap[$style->th_product_id_uix])) {
                $key = $productIdMap[$style->th_product_id_uix];
                $product = $products[$key];

                $product['isOutdated'] = $this->isTHStyleOutdated($style, $product);
                $product['installed'] = $style;

                $products[$key] = $product;
            }
        }

        return $products;
    }

    protected function isTHStyleOutdated(\XF\Entity\Style $style, Array $product)
    {
        if (version_compare($style->th_product_version_uix, $product['latest_version']) === -1) {
            return true;
        }

        return false;
    }

    public function findStylesByTHProductIds(Array $productIds)
    {
        return $this->finder('XF:Style')->where('th_product_id_uix', $productIds)->fetch();
    }
}