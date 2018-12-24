<?php

namespace ThemeHouse\UIX\Service\Style;

class Downloader extends \XF\Service\AbstractService
{
    protected $apiUrl = 'products/{product_id}/download/{version_id}';

    public function download($productId, $versionId)
    {
        $downloadResponse = $this->getVersion($productId, $versionId);

        if ($downloadResponse['status'] === 'error') {
            return $downloadResponse;
        }

        $version = $downloadResponse['payload']['version'];

        return $this->downloadStyle($version);
    }

    protected function downloadStyle($version)
    {
        /** @var \ThemeHouse\UIX\Service\ApiRequest $apiService */
        $apiService = $this->service('ThemeHouse\UIX:ApiRequest');

        $tempPath = \XF\Util\File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time . '.zip';

        $downloadResponse = $apiService->download($version['download_url'], $tempPath);

        if ($downloadResponse['status'] === 'error') {
            return [
                'status' => 'error',
                'error' => 'Unable to download style zip',
            ];
        }

        return $downloadResponse;
    }

    protected function getVersion($productId, $versionId)
    {
        /** @var \ThemeHouse\UIX\Service\ApiRequest $apiService */
        $apiService = $this->service('ThemeHouse\UIX:ApiRequest');

        $url = str_replace('{product_id}', $productId, str_replace('{version_id}', $versionId, $this->apiUrl));
        return $apiService->get($url);
    }
}