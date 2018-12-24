<?php

namespace XenGenTr\ResimliOneCikanKonular\Repository;

use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Repository;

class ResimliOnecikan extends Repository
{
	public function findResimliOnecikan()
	{
		return $this->finder('XenGenTr\ResimliOneCikanKonular:ResimliOnecikan')
			->with('Thread', true)
			->order('resimlionecikan_tarih', 'DESC');
	}
	
	public function fetchResimliOnecikanByThread($thread)
	{
		return $this->finder('XenGenTr\ResimliOneCikanKonular:ResimliOnecikan')
			->where('thread_id', $thread->thread_id)
			->fetchOne();
	}
	
	public function parseResimliOnecikanlar($resimlionecikanlar, $trim = 0)
	{
		foreach ($resimlionecikanlar AS &$resimlionecikan)
		{
			$resimlionecikan = $this->parseResimliOnecikan($resimlionecikan, $trim);
		}
		
		return $resimlionecikanlar;
	}
	
	public function parseResimliOnecikan($resimlionecikan, $trim = 0)
	{

        $options = $this->options;
        $trim = \XF::options()->XenGenTr_ResimliKonular_limit;


		if (empty($resimlionecikan->resimlionecikan_icerik))
		{
			$resimlionecikan->resimlionecikan_icerik = $resimlionecikan->Thread->FirstPost->message;
		}
		
		$resimlionecikan->resimlionecikan_icerik = str_replace(["\r","\n"], ' ', $resimlionecikan->resimlionecikan_icerik);
		
		$formatter = \XF::app()->stringFormatter();
		$resimlionecikan->resimlionecikan_icerik = $formatter->snippetString($resimlionecikan->resimlionecikan_icerik, $trim, ['stripBbCode' => true]);
		
		return $resimlionecikan;
	}
	
	public function setResimliOnecikanFromUpload($thread, $upload)
	{
		$upload->requireImage();

		if (!$upload->isValid($errors))
		{
			throw new \XF\PrintableException(reset($errors));
		}
		
		$target = 'data://XenGenTr/xengentr_resimlikonular/'.$thread->thread_id.'.jpg';
		$dimensions = \XF::options()->XenGenTr_ResimliKonular_Boyutlari;
		$width = $dimensions['width'];
		$height = $dimensions['height'];
			
		try
		{
			$image = \XF::app()->imageManager->imageFromFile($upload->getTempFile());
			
			$ratio = $width / $height;
			$w = $image->getWidth();
			$h = $image->getHeight();
			
			if ($w / $h > $ratio)
			{
				$image->resizeTo($w * ($height / $h), $height);
			}
			else
			{
				$image->resizeTo($width, $h * ($width / $w));
			}

			$w = $image->getWidth();
			$h = $image->getHeight();
			$offWidth = ($w - $width) / 2;
			$offHeight = ($h - $height) / 2;

			$image->crop($width, $height, $offWidth, $offHeight);
			
			$tempFile = \XF\Util\File::getTempFile();
			if ($tempFile && $image->save($tempFile))
			{
				$output = $tempFile;
			}
			unset($image);
			
			\XF\Util\File::copyFileToAbstractedPath($output, $target);
		}
		catch (Exception $e)
		{
			throw new \XF\PrintableException(\XF::phrase('XenGenTr_ResimliKonular_hata_olustu'));
		}
	}
}