<?php

namespace ThemeHouse\UIX\Util;

class UIX
{
    public function getAdditionalHtmlClasses(\XF\Template\Templater $templater, array $params = [])
    {
        $classes = [];
        if (!empty($params['breadcrumbs'])) {
            $classes[] = 'uix_hasCrumbs';
        }

        if (!empty($params['pageAction'])) {
            $classes[] = 'uix_hasPageAction';
        }

        if (!empty($classes)) {
            return ' ' . implode(' ', $classes);
        }

        return '';
    }

    public function getFooterWidgets(\XF\Template\Templater $templater)
    {
        $footerWidgets = false;
        if ($templater->getStyle()->getProperty('uix_enableExtendedFooter')) {
            $footerWidgets = $templater->widgetPosition('th_footer_uix');
        }

        return $footerWidgets;
    }

    public function getSidebarNavWidgets(\XF\Template\Templater $templater)
    {
        $sidebarNavWidgets = false;
        if ($templater->getStyle()->getProperty('uix_navigationType') === 'sidebarNav') {
            $sidebarNavWidgets = $templater->widgetPosition('th_sidebarNavigation_uix');
        }

        return $sidebarNavWidgets;
    }

    public function showWelcomeSection(\XF\Template\Templater $templater, $contentTemplate)
    {
        $visitor = \XF::visitor();

        $showWelcomeSection = false;
        $welcomeSectionVisible = $templater->getStyle()->getProperty('uix_welcomeSectionVisible');
        if ($welcomeSectionVisible !== 'off') {
            switch ($welcomeSectionVisible) {
                case 'guests':
                    $showWelcomeSection = $visitor->user_id === 0;
                    break;
                case 'always':
                    $showWelcomeSection = true;
                    break;
                case 'userPermissions':
                    $showWelcomeSection = $visitor->hasPermission('th_uix', 'showWelcomeSection');
                    break;
            }
        }

        if ($showWelcomeSection) {
            if ($templater->getStyle()->getProperty('uix_welcomeSectionForumListOnly') && $contentTemplate !== 'forum_list') {
                return false;
            }
        }

        return $showWelcomeSection;
    }

    public function getPageWidth(\XF\Template\Templater $templater)
    {
        $session = \XF::session();
        $visitor = \XF::visitor();

        $pageWidth = $templater->getStyle()->getProperty('uix_pageWidthToggle');
        if ($pageWidth && $pageWidth !== 'disabled') {
            $sessionValue = $session->get('th_uix_widthToggle');
            $canTogglePageWidth = $visitor->hasPermission('th_uix', 'togglePageWidth');
            if ($canTogglePageWidth && $sessionValue) {
                if ($sessionValue === 'fixed') {
                    $pageWidth = 'fixed';
                } else {
                    $pageWidth = 'fluid';
                }
            }

            return [
                'canTogglePageWidth' => $canTogglePageWidth,
                'pageWidth' => $pageWidth,
            ];
        }

        return false;
    }

    public function canWriteToJsAndStyleDirectories()
    {
        $baseDir = \XF::getRootDirectory();
        $dirs = [
            $baseDir . '/js',
            $baseDir . '/styles'
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir) || !is_writable($dir)) {
                return false;
            }
        }

        return true;
    }


    public function createFtpConnection($host, $port, $user, $password, $directory)
    {
        return new \ThemeHouse\UIX\Util\Ftp($host, $port, $user, $password, $directory);
    }
}