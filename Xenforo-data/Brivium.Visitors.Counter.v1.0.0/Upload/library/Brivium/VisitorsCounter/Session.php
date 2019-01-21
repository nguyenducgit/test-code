<?php
class Brivium_VisitorsCounter_Session extends XFCP_Brivium_VisitorsCounter_Session
{
	public function saveSessionToSource($sessionId, $isUpdate)
	{
		$config = $this->_config;
		if (!empty($config['table']) && $config['table'] == 'xf_session' &&  !$this->_cache && !$isUpdate)
		{
			$db = $this->_db;
			$db->query( '
				INSERT IGNORE INTO xf_brivium_visitors_view_date
					(session_id, view_date)
				VALUES (?, ?)
			', array($sessionId, XenForo_Application::$time));
		}
		parent::saveSessionToSource($sessionId, $isUpdate);
	}
}