<?php

class Brivium_StyliumFramework_ViewPublic_Helper_Style
{
	protected static $_styliumValue = null;

	public static function setStyliumValue(array $styliumValue)
	{
		self::$_styliumValue = $styliumValue;
	}
	public static function selectorClean($selector)
	{
		$selector = trim($selector);
		if($selector){
			if(substr($selector, 0, 1) == ','){
				$selector = substr($selector, 1);
			}
			if(substr($selector, -1) == ','){
				$selector = substr($selector, 0, strlen($selector)-1);
			}
		}
		return $selector;
	}

	public static function bodyClasses($bodyClasses)
	{
		if(!empty(self::$_styliumValue['layout'])){
			$bodyClasses .= ' '.self::$_styliumValue['layout'];
		}
		return $bodyClasses;
	}

	public static function styleThumb($styleId, $styleTitle)
	{
		$src = 'styles/stylium/thumbs/' . $styleTitle . '.png';
		$image = '';
		if(file_exists($src)){
			$image = "<img class=\"styleThumb style_$styleId\" src=\"{$src}\" alt=\"{$styleTitle}\" />";
		}

		return $image;
	}
}