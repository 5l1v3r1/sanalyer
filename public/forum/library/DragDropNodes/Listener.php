<?php

class DragDropNodes_Listener
{
	public static function load_class_controller($class, array &$extend)
	{
		if ($class == 'XenForo_ControllerAdmin_Node')
		{
			$extend[] = 'DragDropNodes_ControllerAdmin_Node';
		}
	}
	
	public static function load_class_model($class, array &$extend)
	{
		if ($class == 'XenForo_Model_Node')
		{
			$extend[] = 'DragDropNodes_Model_Node';
		}
	}
}