<?php
class Brivium_VisitorsCounter_EventListeners_Listener extends Brivium_BriviumHelper_EventListeners
{
	public static function templateHook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
	{
		$options = XenForo_Application::get('options');
		switch ($hookName) 
		{
			case 'BRVC_visitors_counter':
				$visitorsCounter = self::_getVisitorsCounterToRender();
				if(!empty($visitorsCounter))
				{
					$newTemplate = $template->create('BRVC_visitors_counter', $template->getParams());
					$newTemplate->setParam('visitorsCounter', $visitorsCounter);
					$contents .= $newTemplate->render();
				}
				break;
		}
	}
	
	public static function widgetFrameworkReady(&$renderers)
	{
		if (!in_array( 'Brivium_VisitorsCounter_WidgetRenderer_VisitorsCounter', $renderers))
		{
			$renderers[] = 'Brivium_VisitorsCounter_WidgetRenderer_VisitorsCounter';
		}
	}
	
	private static $_visitorsCounter = null;
	public static function _getVisitorsCounterToRender()
	{
		if(self::$_visitorsCounter === null)
		{
			$visitorsCounterModel = XenForo_Model::create('Brivium_VisitorsCounter_Model_VisitorsCounter');
			$visitorsCounter = $visitorsCounterModel->getVisitorsCounter();
			self::$_visitorsCounter = $visitorsCounterModel->renderToTitleAndValue($visitorsCounter);
		}
		return self::$_visitorsCounter;
	}
}