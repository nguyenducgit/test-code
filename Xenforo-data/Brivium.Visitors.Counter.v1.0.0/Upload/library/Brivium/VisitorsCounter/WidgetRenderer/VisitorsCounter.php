<?php
class Brivium_VisitorsCounter_WidgetRenderer_VisitorsCounter extends WidgetFramework_WidgetRenderer
{
	public function extraPrepareTitle(array $widget)
	{
		if(empty($widget['title']))
		{
			return new XenForo_Phrase('BRVC_visitors_counter');
		}
		return parent::extraPrepareTitle($widget);
	}

	protected function _getConfiguration()
	{
		return array(
			'name' => new XenForo_Phrase('BRVC_visitors_counter'),
			'useCache' => true,
			'useUserCache' => true,
			'cacheSeconds' => 60, // cache for 1 hour
		);
	}
	protected function _getOptionsTemplate()
	{
		return '';
	}

	protected function _getRenderTemplate(array $widget, $positionCode, array $params)
	{
		return 'BRVC_visitors_counter';
	}

	protected function _render(array $widget, $positionCode, array $params, XenForo_Template_Abstract $renderTemplateObject)
	{
		$visitorsCounter = Brivium_VisitorsCounter_EventListeners_Listener::_getVisitorsCounterToRender();
		if(empty($visitorsCounter))
		{
			return '';
		}
		$renderTemplateObject->setParam('visitorsCounter', $visitorsCounter);
		$renderTemplateObject->setParam('isWidgetRender', true);
		return $renderTemplateObject->render();
	}
}