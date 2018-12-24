<?php

class Brivium_ModernStatistic_ControllerPublic_Account extends XFCP_Brivium_ModernStatistic_ControllerPublic_Account
{
	public function actionBrmsStatisticsPreferences()
	{
		$modernStatisticModel = $this->_getModernStatisticModel();
		$modernStatistics = $modernStatisticModel->getActiveModernStatistics();
		$visitor = XenForo_Visitor::getInstance()->toArray();
		$statisticPerferences = !empty($visitor['brms_statistic_perferences'])?@unserialize($visitor['brms_statistic_perferences']):array();
		$viewParams = array(
			'modernStatistics' => $modernStatistics,
			'statisticPerferences' => $statisticPerferences,
		);
		
		return $this->_getWrapper(
			'account', 'BrmsStatisticsPreferences',
			$this->responseView(
				'XenForo_ViewPublic_Account_StatisticsPreferences',
				'BRMS_modern_statistics_perference',
				$viewParams
			)
		);
	}
	
	public function actionBrmsStatisticsPreferencesSave()
	{
		$this->_assertPostOnly();
		$data = $this->_input->filter(array(
			'brms_statistic_perferences'    => XenForo_Input::ARRAY_SIMPLE,
		));
		$writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
		if (!$writer = $this->_saveVisitorSettings($data, $errors))
		{
			return $this->responseError($errors);
		}

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildPublicLink('account/brms-statistics-preferences')
		);
	}
	
	
	/**
	 * Gets the product model.
	 *
	 * @return Brivium_ModernStatistic_Model_ModernStatistic
	 */
	protected function _getModernStatisticModel()
	{
		return $this->getModelFromCache('Brivium_ModernStatistic_Model_ModernStatistic');
	}
	
}