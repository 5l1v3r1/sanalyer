<?php

namespace ThemeHouse\UIX\Service\Style;

class Fetcher extends \XF\Service\AbstractService
{
    protected $productListingUrl = 'product-categories/8';
    protected $productUrl = 'products/{id}';

    public function fetch($productId = 0)
    {
        /** @var \ThemeHouse\UIX\Service\ApiRequest $apiService */
        $apiService = $this->service('ThemeHouse\UIX:ApiRequest');

        $url = $this->productListingUrl;

        if ($productId) {
            $url = str_replace('{id}', $productId, $this->productUrl);
        }

        $product = $apiService->get($url);

        if ($productId) {
            $product['payload']['versions'] = array_reverse($product['payload']['versions']);
        }

        return $product;
    }
}