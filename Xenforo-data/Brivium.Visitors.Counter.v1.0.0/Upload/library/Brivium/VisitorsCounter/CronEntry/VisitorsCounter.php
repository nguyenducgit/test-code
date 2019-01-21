<?php
class Brivium_VisitorsCounter_CronEntry_VisitorsCounter
{
	public static function runCounterUpdate()
	{
		XenForo_Model::create('Brivium_VisitorsCounter_Model_VisitorsCounter')->updateVisitorsCounter();
	}
}