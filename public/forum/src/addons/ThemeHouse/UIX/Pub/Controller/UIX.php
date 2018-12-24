<?php

namespace ThemeHouse\UIX\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class UIX extends AbstractController
{
    public function preDispatch($action, ParameterBag $params)
    {
        if ($this->responseType() !== 'json') {
            throw $this->exception($this->notFound());
        }
    }

    public function actionToggleWidth()
    {
        $sessionKey = 'th_uix_widthToggle';
        $session = $this->session();
        $newWidth = $this->filter('width', 'string');
        if ($newWidth !== 'fluid') {
            $newWidth = 'fixed';
        }

        $visitor = \XF::visitor();
        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int) $this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        $propertyValue = $style->getProperty('uix_pageWidthToggle');
        if (!$propertyValue || $propertyValue === 'disabled') {
            return $this->notFound();
        }

        if (!$visitor->hasPermission('th_uix', 'togglePageWidth')) {
            return $this->noPermission();
        }

        $session->set($sessionKey, $newWidth);

        $viewParams = [
            'width' => $newWidth,
        ];

        return $this->view('ThemeHouse\UIX:UIX\ToggleWidth', '', $viewParams);
    }

    public function actionToggleCategory()
    {
        $sessionKey = 'th_uix_collapsedCategories';
        $session = $this->session();
        $nodeId = $this->filter('node_id', 'uint');
        $collapsed = $this->filter('collapsed', 'bool');

        $visitor = \XF::visitor();
        if (!$visitor->hasPermission('th_uix', 'collapseCategories')) {
            return $this->noPermission();
        }

        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int) $this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        if (!$style->getProperty('uix_categoryCollapse')) {
            return $this->notFound();
        }

        /** @var \XF\Entity\Category $node */
        $category = $this->assertRecordExists('XF:Category', $nodeId);
        /** @var \XF\Entity\Node $node */
        $node = $category->Node;

        if (!$node->canView($error) || $node->depth !== 0) {
            return $this->noPermission($error);
        }

        $collapsedCategories = $session->get($sessionKey);
        if (empty($collapsedCategories)) {
            $collapsedCategories = [];
        }

        $currentState = in_array($nodeId, $collapsedCategories);

        if ($collapsed && !$currentState) {
            $collapsedCategories[] = $nodeId;
        }

        if (!$collapsed && $currentState) {
            foreach ($collapsedCategories as $key=>$thisNodeId) {
                if ($thisNodeId === $nodeId) {
                    unset($collapsedCategories[$key]);
                }
            }
        }

        $session->set($sessionKey, $collapsedCategories);

        $viewParams = [
            'collapsed' => $collapsed,
            'collapsedCategories' => $collapsedCategories,
        ];

        return $this->view('ThemeHouse\UIX:UIX\ToggleCategory', '', $viewParams);
    }

    public function actionToggleSidebar()
    {
        $sessionKey = 'th_uix_sidebarCollapsed';
        $session = $this->session();
        $collapsed = $this->filter('collapsed', 'bool');

        $visitor = \XF::visitor();
        if (!$visitor->hasPermission('th_uix', 'collapseSidebar')) {
            return $this->noPermission();
        }

        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int) $this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        if (!$style->getProperty('uix_collapsibleSidebar')) {
            return $this->notFound();
        }

        $session->set($sessionKey, $collapsed);

        $viewParams = [
            'collapsed' => $collapsed,
        ];

        return $this->view('ThemeHouse\UIX:UIX\Toggle', '', $viewParams);
    }

    public function actionToggleSidebarNavigation()
    {
        $sessionKey = 'th_uix_sidebarNavCollapsed';
        $session = $this->session();
        $collapsed = $this->filter('collapsed', 'bool');

        $visitor = \XF::visitor();

        $styleId = $visitor->style_id;
        if (!$styleId) {
            $styleId = (int) $this->app()->options()->defaultStyleId;
        }

        $style = $this->app()->style($styleId);
        if ($style->getProperty('uix_navigationType') !== 'sidebarNav') {
            return $this->notFound();
        }

        $session->set($sessionKey, $collapsed);

        $viewParams = [
            'collapsed' => $collapsed,
        ];

        return $this->view('ThemeHouse\UIX:UIX\Toggle', '', $viewParams);
    }

    public function assertValidCsrfToken($token = null, $validityPeriod = null)
    {
        return false;
    }
}