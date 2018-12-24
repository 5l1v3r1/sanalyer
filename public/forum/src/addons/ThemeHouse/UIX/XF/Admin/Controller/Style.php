<?php

namespace ThemeHouse\UIX\XF\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Util\File;

class Style extends XFCP_Style
{
    protected $ftpConnection;
    protected $sessionKey = 'th_styleInstallUpgrade_uix';

    public function preDispatch($action, ParameterBag $params)
    {
        parent::preDispatch($action, $params);

        $blockedActions = [
//            'Themehouse',
            'ThemehouseInstall',
            'ThemehouseUpgrade',
        ];

        if (in_array($action, $blockedActions)) {
            $update = \XF::service('ThemeHouse\UIX:UpdateCheck', 217, \XF::repository('XF:AddOn')->inferVersionStringFromId(\XF::app()->registry()['addOns']['ThemeHouse/UIX']));
            $bypassCheck = \XF::config('uix_bypassCheck');
            if (empty($bypassCheck)) {
                $check = $update->check();

                if (!$check || $check['requires_update']) {
                    throw $this->exception($this->error('The UI.X add-on requires an update before you can upgrade this style.'));
                }
            }

            $uix = new \ThemeHouse\UIX\Util\UIX;
            $canWriteDirs = $uix->canWriteToJsAndStyleDirectories();
            if (!$canWriteDirs && !\XF::options()->th_enableFtp_uix) {
                throw $this->exception($this->error('Your XenForo directory is not writable by the PHP user. You can contact your host and recommend they use a method that allows PHP to run as the same user that owns the XenForo files or alternatively you can <a href="https://www.themehouse.com/help/documentation/uix2/installing-uix-2-manually">install your style manually</a>, a future update will add back the ability to install via FTP.'));
            }

            if (!class_exists('ZipArchive')) {
                throw $this->exception($this->error('The PHP zip extension has not been installed and is required for this functionality to work. Please contact your host to get this issue resolved as this is not something we\'re able to fix from the add-on'));
            }
        }
    }

    public function actionThemehouse() {
        $uix = new \ThemeHouse\UIX\Util\UIX;
        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');
        /** @var \XF\Repository\Style $styleRepo */
        $styleRepo = $this->repository('XF:Style');

        $styleResponse = $styleFetcher->fetch();
        if ($styleResponse['status'] === 'error') {
            return $this->thStyleErrorResponse($styleResponse);
        }
        $styles = $styleResponse['payload']['products'];
        $styles = $styleRepo->prepareTHStyles($styles);

        $viewParams = [
            'canWriteDirs' => $uix->canWriteToJsAndStyleDirectories(),
            'styles' => $styles,
        ];
        return $this->view('ThemeHouse\UIX:Style\ThemeHouse', 'th_style_list_uix', $viewParams);
    }

    public function actionThemeHouseChildStyles()
    {
        $options = \XF::options();

        $uix = new \ThemeHouse\UIX\Util\UIX;

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->filter('product_id', 'uint');
        $styleId = $this->filter('style_id', 'uint');

        $style = $this->assertStyleExists($styleId);

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];

        $viewParams = [
            'canWriteDirs' => $uix->canWriteToJsAndStyleDirectories(),
            'product' => $product,
            'style' => $style,
            'refreshUrl' => $this->buildLink('styles/themehouse/child-styles/refresh', null, [
                'product_id' => $productId,
                'style_id' => $styleId,
            ]),
        ];

        return $this->view('ThemeHouse\UIX:Style\ThemeHouse\ChildStyles', 'th_child_style_list_uix', $viewParams);
    }

    public function actionThemeHouseChildStylesInstall()
    {
        $options = \XF::options();

        $uix = new \ThemeHouse\UIX\Util\UIX;

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->filter('product_id', 'uint');
        $styleId = $this->filter('style_id', 'uint');
        $childStyle = $this->filter('child_style', 'str');

        $style = $this->assertStyleExists($styleId);

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];
        $latestVersion = $styleResponse['payload']['versions'][0];

        /** @var \ThemeHouse\UIX\Service\Style\Downloader $styleDownloader */
        $styleDownloader = $this->service('ThemeHouse\UIX:Style\Downloader');
        /** @var \ThemeHouse\UIX\Service\Style\Extractor $styleExtractor */
        $styleExtractor = $this->service('ThemeHouse\UIX:Style\Extractor');


        $primaryChildStyle = $this->finder('XF:Style')->where('parent_id', '=', $style->style_id)->where('th_primary_child_uix', '=', 1)->fetchOne();
        if (!$primaryChildStyle) {
            return $this->error('Unable to locate primary Tactical child style');
        }

        $tempStyleDir = \XF\Util\File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time;

        $downloadResponse = $styleDownloader->download($product['id'], $latestVersion['id']);
        if ($downloadResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($downloadResponse['error']);
        }

        $extractorResponse = $styleExtractor->extract($downloadResponse['path'], $tempStyleDir);
        if ($extractorResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($extractorResponse['error']);
        }

        $childStyles = $this->repository('ThemeHouse\UIX:StyleInstaller')->getStyleNamesFromXmls($extractorResponse['path'], $extractorResponse['childStyles']);

        $style->th_child_style_cache_uix = $childStyles;
        $style->save();

        if (empty($childStyles[$childStyle])) {
            File::cleanUpTempFiles();
            return $this->error('Could not find child style');
        }

        $xmlFile = $tempStyleDir . DIRECTORY_SEPARATOR . 'child_xmls' . DIRECTORY_SEPARATOR . $childStyle;

        /** @var \ThemeHouse\UIX\Service\Style\Installer $installerService */
        $installerService = $this->service('ThemeHouse\UIX:Style\Installer', $product, $latestVersion);
        $installerResponse = $installerService->importStyle($xmlFile, $primaryChildStyle);
        $installerService->createChildStyle($installerResponse['style']);

        File::deleteDirectory($tempStyleDir);

        return $this->redirect($this->buildLink('styles'));
    }

    public function actionThemeHouseChildStylesRefresh()
    {
        $options = \XF::options();

        $uix = new \ThemeHouse\UIX\Util\UIX;

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->filter('product_id', 'uint');
        $styleId = $this->filter('style_id', 'uint');

        $style = $this->assertStyleExists($styleId);

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];
        $latestVersion = $styleResponse['payload']['versions'][0];

        /** @var \ThemeHouse\UIX\Service\Style\Downloader $styleDownloader */
        $styleDownloader = $this->service('ThemeHouse\UIX:Style\Downloader');
        /** @var \ThemeHouse\UIX\Service\Style\Extractor $styleExtractor */
        $styleExtractor = $this->service('ThemeHouse\UIX:Style\Extractor');

        $tempStyleDir = \XF\Util\File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time;

        $downloadResponse = $styleDownloader->download($product['id'], $latestVersion['id']);
        if ($downloadResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($downloadResponse['error']);
        }

        $extractorResponse = $styleExtractor->extract($downloadResponse['path'], $tempStyleDir);
        if ($extractorResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            return $this->error($extractorResponse['error']);
        }

        $childStyles = $this->repository('ThemeHouse\UIX:StyleInstaller')->getStyleNamesFromXmls($extractorResponse['path'], $extractorResponse['childStyles']);

        File::deleteDirectory($tempStyleDir);

        $style->th_child_style_cache_uix = $childStyles;
        $style->save();

        return $this->redirect($this->buildLink('styles/themehouse/child-styles', null, [
            'product_id' => $productId,
            'style_id' => $styleId,
        ]));
    }

    public function actionThemehouseInstall()
    {
        $options = \XF::options();

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->filter('product_id', 'int');
        $versionId = $this->filter('version_id', 'int');

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $product = $styleResponse['payload']['product'];
        $versions = $styleResponse['payload']['versions'];

        if ($this->isPost()) {
            $step = $this->filter('step', 'string');

            if ($step === 'ftp_details' && $options->th_enableFtp_uix) {
                $viewParams = [
                    'product' => $product,
                    'submitUrl' => $this->buildLink('styles/themehouse/install', null, ['product_id' => $product['id']]),
                    'versionId' => $versionId,
                    'fileDir' => \XF::getRootDirectory(),
                ];
                return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall\FtpDetails', 'th_style_install_ftp_uix', $viewParams);
            }

            return $this->installUpgradeStyle($product, $versions);
        }

        $viewParams = [
            'product' => $product,
            'versions' => $versions,
            'freshInstall' => true,
            'submitUrl' => $this->buildLink('styles/themehouse/install', null, ['product_id' => $product['id']]),
        ];

        return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall\Version', 'th_style_install_version_uix', $viewParams);
    }


    public function actionThemehouseUpgrade()
    {
        $options = \XF::options();

        /** @var \ThemeHouse\UIX\Service\Style\Fetcher $styleFetcher */
        $styleFetcher = $this->service('ThemeHouse\UIX:Style\Fetcher');

        $productId = $this->request()->filter('product_id', 'int');
        $styleId = $this->request()->filter('style_id', 'int');
        $versionId = $this->filter('version_id', 'int');

        $styleResponse = $styleFetcher->fetch($productId);
        if ($styleResponse['status'] === 'error') {
            return $this->error($styleResponse['error']);
        }

        $style = $this->assertStyleExists($styleId);

        $product = $styleResponse['payload']['product'];
        $versions = $styleResponse['payload']['versions'];

        if ($this->isPost()) {
            $step = $this->filter('step', 'string');

            if ($step === 'ftp_details' && $options->th_enableFtp_uix) {
                $viewParams = [
                    'product' => $product,
                    'style' => $style,
                    'submitUrl' => $this->buildLink('styles/themehouse/upgrade', null, ['product_id' => $product['id'], 'style_id' => $style->style_id]),
                    'versionId' => $versionId,
                    'fileDir' => \XF::getRootDirectory(),
                ];
                return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall\FtpDetails', 'th_style_install_ftp_uix', $viewParams);
            }

            return $this->installUpgradeStyle($product, $versions, $style);
        }

        $viewParams = [
            'product' => $product,
            'versions' => $versions,
            'style' => $style,
            'freshInstall' => false,
            'submitUrl' => $this->buildLink('styles/themehouse/upgrade', null, ['product_id' => $product['id'], 'style_id' => $style->style_id]),
        ];

        return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall', 'th_style_install_version_uix', $viewParams);
    }

    protected function installUpgradeStyle(Array $product, Array $versions, \XF\Entity\Style $style = null)
    {
        $submitUrl = $this->buildLink('styles/themehouse/install', null, ['product_id' => $product['id']]);
        if ($style) {
            $submitUrl = $this->buildLink('styles/themehouse/upgrade', null, ['product_id' => $product['id'], 'style_id' => $style->style_id]);
        }

        $options = \XF::options();
        $uix = new \ThemeHouse\UIX\Util\UIX;
        $path = $this->filter('path', 'str');

        $ftpDetails = $this->filter([
            'ftp_host' => 'string',
            'ftp_port' => 'uint',
            'ftp_user' => 'string',
            'ftp_pass' => 'string',
            'ftp_dir' => 'string',
        ]);

        if ($options->th_enableFtp_uix) {
            try {
                $this->ftpConnection = $uix->createFtpConnection($ftpDetails['ftp_host'], $ftpDetails['ftp_port'], $ftpDetails['ftp_user'], $ftpDetails['ftp_pass'], $ftpDetails['ftp_dir']);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }

        $freshInstall = true;
        if ($style) {
            $freshInstall = false;
        }

        $versionId = $this->filter('version_id', 'int');
        $tempStyleDir = \XF\Util\File::getTempDir() . DIRECTORY_SEPARATOR . 'style-' . \XF::$time;
        if ($path) {
            $tempStyleDir = $path;
        }
        $version = false;

        foreach ($versions as $thisVersion) {
            if ($thisVersion['id'] === $versionId) {
                $version = $thisVersion;
                break;
            }
        }

        if (!$version) {
            File::cleanUpTempFiles();
            return $this->error('The version you have requested was not found');
        }

        /** @var \ThemeHouse\UIX\Service\Style\Downloader $styleDownloader */
        $styleDownloader = $this->service('ThemeHouse\UIX:Style\Downloader');
        /** @var \ThemeHouse\UIX\Service\Style\Extractor $styleExtractor */
        $styleExtractor = $this->service('ThemeHouse\UIX:Style\Extractor');

        $childStyles = false;
        $childStylePaths = [];
        if (!is_dir($path)) {
            $downloadResponse = $styleDownloader->download($product['id'], $versionId);
            if ($downloadResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                return $this->error($downloadResponse['error']);
            }

            $extractorResponse = $styleExtractor->extract($downloadResponse['path'], $tempStyleDir);
            if ($extractorResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                return $this->error($extractorResponse['error']);
            }

            $childStyleNames = $this->repository('ThemeHouse\UIX:StyleInstaller')->getStyleNamesFromXmls($extractorResponse['path'], $extractorResponse['childStyles']);
            $this->session()->set($this->sessionKey, $childStyleNames);

            if (!empty($extractorResponse['childStyles']) && $freshInstall) {

                $viewParams = [
                    'path' => $extractorResponse['path'],
                    'childStyles' => $childStyleNames,
                    'ftpDetails' => $ftpDetails,
                    'submitUrl' => $submitUrl,
                    'versionId' => $versionId,
                ];
                return $this->view('ThemeHouse\UIX:Style\ThemeHouseInstall', 'th_style_install_child_uix', $viewParams);
            }
        } else {
            $childStyles = $this->filter('child_styles', 'array');
            foreach ($childStyles as $childStyle) {
                $childStylePath = $path . DIRECTORY_SEPARATOR . 'child_xmls' . DIRECTORY_SEPARATOR . $childStyle;

                if (!file_exists($childStylePath)) {
                    return $this->error('Unable to find the requested child style');
                }

                $childStylePaths[$childStyle] = $childStylePath;
            }
        }

        $childStyleNames = $this->session()->get($this->sessionKey);

        /** @var \ThemeHouse\UIX\Service\Style\Mover $styleMover */
        $styleMover = $this->service('ThemeHouse\UIX:Style\Mover', $tempStyleDir, \XF::getRootDirectory(), $this->ftpConnection);
        $moverResponse = $styleMover->move();

        if ($moverResponse['status'] === 'error') {
            File::cleanUpTempFiles();
            $this->session()->remove($this->sessionKey);
            return $this->error($moverResponse['error']);
        }

        if ($freshInstall) {
            /** @var \ThemeHouse\UIX\Service\Style\Installer $styleInstaller */
            $styleInstaller = $this->service('ThemeHouse\UIX:Style\Installer', $product, $version);

            $installResponse = $styleInstaller->install($tempStyleDir, $childStylePaths, $childStyleNames);

            if ($installResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                $this->session()->remove($this->sessionKey);
                return $this->error($installResponse['error']);
            }
        } else {
            /** @var \ThemeHouse\UIX\Service\Style\Upgrader $styleUpgrader */
            $styleUpgrader = $this->service('ThemeHouse\UIX:Style\Upgrader', $product, $version, $style);

            $upgradeResponse = $styleUpgrader->upgrade($tempStyleDir, $childStyleNames);

            if ($upgradeResponse['status'] === 'error') {
                File::cleanUpTempFiles();
                $this->session()->remove($this->sessionKey);
                return $this->error($upgradeResponse['error']);
            }
        }

        File::cleanUpTempFiles();
        $this->session()->remove($this->sessionKey);
        \ThemeHouse\UIX\Cron\UpdateCheck::run();
        return $this->redirect($this->buildLink('styles/themehouse'));
    }

    protected function thStyleErrorResponse($response)
    {
        switch ($response['error_code']) {
            case 'ERR_API_KEY':
                $error = \XF::phrase('th_invalid_api_key_uix');
                break;

            default:
                $error = $response['error'];
                break;
        }
        return $this->error($error);
    }
}