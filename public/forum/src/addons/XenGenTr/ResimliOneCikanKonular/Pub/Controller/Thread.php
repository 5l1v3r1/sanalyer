<?php

namespace XenGenTr\ResimliOneCikanKonular\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread

{
	public function actionIndex(ParameterBag $params)
	{
		$reply = parent::actionIndex($params);
		
		if (!$reply instanceof \XF\Mvc\Reply\View)
		{
			return $reply;
		}
		
		$thread = $reply->getParam('thread');
		
		$reply->setParam('resimlionecikan', $this->repository('XenGenTr\ResimliOneCikanKonular:ResimliOnecikan')->fetchResimliOnecikanByThread($thread));
		
		return $reply;
	}

	public function actionResimliOneCikanDuzenle(ParameterBag $params)
	{
		if (!\XF::visitor()->hasPermission('ResimliKonular_izinler', 'resimlionecikan_gonder'))
		{
			return $this->noPermission();
		}
		
		$thread = $this->assertViewableThread($params->thread_id);
		$resimlionecikan = $thread->ResimliOnecikan;
		
		if ($resimlionecikan && !$resimlionecikan->canEdit())
		{
			return $this->noPermission();
		}
		
		if ($this->isPost())
		{
			if (!$resimlionecikan)
			{
				$resimlionecikan = $this->em()->create('XenGenTr\ResimliOneCikanKonular:ResimliOnecikan');
			}
			
			if ($upload = $this->request->getFile('upload', false, false))
			{
				$this->repository('XenGenTr\ResimliOneCikanKonular:ResimliOnecikan')->setResimliOnecikanFromUpload($thread, $upload);
			}
			
			$input = $this->filter('resimlionecikan', 'array');
			$input['thread_id'] = $thread->thread_id;
			$input['resimlionecikan_baslik'] = !empty($input['resimlionecikan_baslik']) ? $input['resimlionecikan_baslik'] : '';
			$input['resimlionecikan_icerik'] = !empty($input['resimlionecikan_icerik']) ? $input['resimlionecikan_icerik'] : '';
			
			$date = $this->filter('date', 'datetime');
			$time = $this->filter('time', 'str');
			list ($hour, $min) = explode(':', $time);
			
			$dateTime = new \DateTime('@'.$date);
			$dateTime->setTimeZone(\XF::language()->getTimeZone());
			$dateTime->setTime($hour, $min);
			$input['resimlionecikan_tarih'] = $dateTime->getTimestamp();
			
			$form = $this->formAction();
			$form->basicEntitySave($resimlionecikan, $input);
			$form->run();
			
			return $this->redirect($this->buildLink('threads', $thread));
		}
		
		$viewParams = [
			'thread' => $thread,
			'resimlionecikan' => $resimlionecikan,
		];
		
		return $this->view('XenGenTrResimliOneCikanKonular:Thread\ResimliOnecikanDuzenle', 'XenGenTr_ResimliKonular_duzenle', $viewParams);
	}
	
	public function actionResimliOneCikanSil(ParameterBag $params)
	{
		$resimlionecikan = $this->assertResimliOnecikanExists($params->thread_id, 'Thread');
		
		if (!$resimlionecikan->canEdit())
		{
			return $this->noPermission();
		}
		
		if (!$resimlionecikan->preDelete())
		{
			return $this->error($resimlionecikan->getErrors());
		}

		if ($this->isPost())
		{
			$resimlionecikan->delete();
			return $this->redirect($this->buildLink('threads', $resimlionecikan));
		}
		else
		{
			$viewParams = [
				'resimlionecikan' => $resimlionecikan
			];
			return $this->view('XenGenTr\ResimliOneCikanKonular:ResimliOneCikan\Sil', 'XenGenTr_ResimliKonulari_sil', $viewParams);
		}
	}	
	
	protected function assertResimliOnecikanExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('XenGenTr\ResimliOneCikanKonular:ResimliOnecikan', $id, $with, $phraseKey);
	}
}