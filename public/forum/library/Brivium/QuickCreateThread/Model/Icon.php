<?php
class Brivium_QuickCreateThread_Model_Icon extends XenForo_Model
{
	public static $imageQuality = 80;

	public function uploadIcon(XenForo_Upload $upload, $node, $number, $nodeType)
	{
		$options = XenForo_Application::get('options');

		$iconsProcessed = self::_applyIcon($node, $upload ,$number, $nodeType);

		$this->_writeIcon($node['node_id'],$number,$iconsProcessed);
	}
	protected function _writeIcon($nodeId, $number, $tempFile)
	{
		$filePath = $this->getIconFilePath($nodeId, $number);
		$directory = dirname($filePath);

		if (XenForo_Helper_File::createDirectory($directory, true) && is_writable($directory))
		{
			if (file_exists($filePath))
			{
				@unlink($filePath);
			}

			return XenForo_Helper_File::safeRename($tempFile, $filePath);
		}
		else
		{
			return false;
		}
	}
	public function getIconFilePath($nodeId, $number, $externalDataPath = null)
	{
		if ($externalDataPath === null)
		{
			$externalDataPath = XenForo_Helper_File::getExternalDataPath();
		}

		return sprintf('%s/nodeIcons/'.$nodeId.'_'.$number.'.jpg',
			$externalDataPath
		);
	}
	public function deleteIcon($nodeId, $number, $updateForum = true, $nodeType)
	{
		$filePath = $this->getIconFilePath($nodeId, $number);
		if (file_exists($filePath) && is_writable($filePath))
		{
			@unlink($filePath);
		}

		$size = 0;

		switch($nodeType)
		{
			case 'forum':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_Forum',XenForo_DataWriter::ERROR_SILENT);
				$dwData = array(
					'brqct_icon_date_'.$number => 0
				);
				break;
			case 'page':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_Page',XenForo_DataWriter::ERROR_SILENT);
				$dwData = array(
					'brqct_icon_date' => 0
				);
				break;
			case 'link':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_LinkForum',XenForo_DataWriter::ERROR_SILENT);
				$dwData = array(
					'brqct_icon_date' => 0
				);
				break;
			case 'category':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_Category',XenForo_DataWriter::ERROR_SILENT);
				$dwData = array(
					'brqct_icon_date' => 0
				);
				break;
		}

		$dw->setExistingData($nodeId);
		$dw->bulkSet($dwData);
		$dw->save();

		return $dwData;
	}
	protected static function _applyIcon($node, $upload, $number, $nodeType)
	{
		if (!$upload->isValid()) {
			throw new XenForo_Exception($upload->getErrors(), true);
		}

		if (!$upload->isImage()) {
			throw new XenForo_Exception(new XenForo_Phrase('uploaded_file_is_not_valid_image'), true);
		};

		$imageType = $upload->getImageInfoField('type');
		if (!in_array($imageType, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
			throw new XenForo_Exception(new XenForo_Phrase('uploaded_file_is_not_valid_image'), true);
		}

		$outputFiles = array();
		$fileName = $upload->getTempFile();
		$imageType = $upload->getImageInfoField('type');
		$outputType = $imageType;
		$width = $upload->getImageInfoField('width');
		$height = $upload->getImageInfoField('height');

		$newTempFile = tempnam(XenForo_Helper_File::getTempDir(), 'qct');
		$image = XenForo_Image_Abstract::createFromFile($fileName, $imageType);
		if (!$image) {
			continue;
		}

		$options = XenForo_Application::get('options');
		$size = $options->BRQCT_size_icon;
		$tmpImage = $image;

		if ($image->getWidth() < $size['width'])
		{
			throw new XenForo_Exception(new XenForo_Phrase('BRQCT_uploaded_file_is_not_accordant_image'), true);
		}

		$image->thumbnail($size['width'], 999999);

		if ($image->getHeight() < $size['height'])
		{
			throw new XenForo_Exception(new XenForo_Phrase('BRQCT_uploaded_file_is_not_accordant_image'), true);
		}

		$y = floor(($image->getHeight() - $size['height']) / 2);
		$image->crop(0, $y, $size['width'], $size['height']);
		$image->output($outputType, $newTempFile, self::$imageQuality);
		unset($image);

		$icons = $newTempFile;

		switch($nodeType)
		{
			case 'forum':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_Forum');
				$dwData = array(
					'brqct_icon_date_'.$number => XenForo_Application::$time,
					'brqct_select' => 'file'
				);
				break;
			case 'page':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_Page');
				$dwData = array(
					'brqct_icon_date' => XenForo_Application::$time,
					'brqct_select' => 'file'
				);
				break;
			case 'link':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_LinkForum');
				$dwData = array(
					'brqct_icon_date' => XenForo_Application::$time,
					'brqct_select' => 'file'
				);
				break;
			case 'category':
				$dw = XenForo_DataWriter::create('XenForo_DataWriter_Category');
				$dwData = array(
					'brqct_icon_date' => XenForo_Application::$time,
					'brqct_select' => 'file'
				);
				break;
		}
		$dw->setExistingData($node['node_id']);
		$dw->bulkSet($dwData);
		$dw->save();

		return $icons;
	}
}