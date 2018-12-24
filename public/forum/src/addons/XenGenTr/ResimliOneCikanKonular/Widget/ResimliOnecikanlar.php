<?php

namespace XenGenTr\ResimliOneCikanKonular\Widget;

class ResimliOnecikanlar extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
		'limit' => 5,
		'trim' => 200,
	];
	
	public function render()
	{   
        /*** kullanýcý sýnýrla ***/
		$visitor = \XF::visitor();				
        if (!$visitor->hasPermission('ResimliKonular_izinler', 'resimlionecikan_gor'))
        {
            return '';
        }
      
        /*** Gösterim limitle ***/
		$options = $this->options;

        $isAddonEnabledGlobally = \XF::options()->offsetGet('XenGenTr_ResimliKonular_kapat');

        if(!$isAddonEnabledGlobally)
        {
            return false;
        }

		$limit = $options['limit'];
        $limit = \XF::options()->XenGenTr_ResimliKonular_gosterim_sayisi;

        /** @var \XenGenTr\ResimliOneCikanKonular\Repository */
		$resimlionecikanRepo = $this->app->repository('XenGenTr\ResimliOneCikanKonular:ResimliOnecikan');

		$entries = $resimlionecikanRepo->findResimliOnecikan()->limit(max($limit * 2, 10))
			->where('Thread.discussion_state', 'visible');
		
		if (!$resimlionecikanlar = $entries->fetch())
		{
			return false;
		}
		
		$resimlionecikanlar = $resimlionecikanlar->slice(0, $limit, true);

		$viewParams = [
			'resimlionecikanlar' => $resimlionecikanRepo->parseResimliOnecikanlar($resimlionecikanlar, $options['trim']),
		];
		
		return $this->renderer('XenGenTr_ResimliKonular_widget', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'trim' => 'uint',
		]);

		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}
		
		return true;
	}
}