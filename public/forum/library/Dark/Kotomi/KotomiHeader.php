<?php
  
require($fileDir . '/library/XenForo/Autoloader.php');
XenForo_Autoloader::getInstance()->setupAutoloader($fileDir . '/library');

XenForo_Application::initialize($fileDir . '/library', $fileDir);
XenForo_Application::set('page_start_time', $startTime);

$kotomi_fc = new Dark_Kotomi_FrontController(new XenForo_Dependencies_Public());

ob_start();