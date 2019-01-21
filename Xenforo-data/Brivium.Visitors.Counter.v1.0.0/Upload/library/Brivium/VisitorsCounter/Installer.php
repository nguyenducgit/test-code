<?php
class Brivium_VisitorsCounter_Installer extends Brivium_BriviumHelper_Installer
{
	protected $_installerType = 1;

	public static function install($existingAddOn, $addOnData)
	{
		self::$_addOnInstaller = __CLASS__;
		if (self::$_addOnInstaller && class_exists( self::$_addOnInstaller))
		{
			$installer = self::create( self::$_addOnInstaller);
			$installer->installAddOn( $existingAddOn, $addOnData);
		}
		return true;
	}

	public static function uninstall($addOnData)
	{
		self::$_addOnInstaller = __CLASS__;
		if (self::$_addOnInstaller && class_exists( self::$_addOnInstaller))
		{
			$installer = self::create( self::$_addOnInstaller);
			$installer->uninstallAddOn( $addOnData);
		}
	}
	public function getTables()
	{
		$tables = array();
	
		$tables['xf_brivium_visitors_view_date'] = "
				CREATE TABLE IF NOT EXISTS `xf_brivium_visitors_view_date` (
				  `session_id` varbinary(32) NOT NULL,
				  `view_date` int(10) unsigned NOT NULL,
				  UNIQUE KEY `session_id` (`session_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				";
		$tables['xf_brivium_visitors_counter'] = "
				CREATE TABLE IF NOT EXISTS `xf_brivium_visitors_counter` (
				  `time_type` varchar(50) NOT NULL,
				  `count` int(10) unsigned NOT NULL DEFAULT '0',
				  `display_order` int(10) unsigned NOT NULL DEFAULT '0',
				  `time_stamps` int(10) unsigned NOT NULL DEFAULT '0',
				  UNIQUE KEY `time_type` (`time_type`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
				";
		return $tables;
	}
	
	protected function _postInstallAfterTransaction()
	{
		XenForo_Model::create('Brivium_VisitorsCounter_Model_VisitorsCounter')->importOriginalDatas();
	}
	
	
	public function getQueryFinal()
	{
		$query = array();
		$query[] = "
			DELETE FROM `xf_brivium_listener_class` WHERE `addon_id` = 'Brivium_VisitorsCounter';
		";
		if($this->_triggerType != "uninstall"){
			$query[] = "
				REPLACE INTO `xf_brivium_addon`
					(`addon_id`, `title`, `version_id`, `copyright_removal`, `start_date`, `end_date`)
				VALUES
					('Brivium_VisitorsCounter', 'Brivium - Visitors Counter', '1000000', 0, 0, 0);
			";
			$query[] = "
				REPLACE INTO `xf_brivium_listener_class`
					(`class`, `class_extend`, `event_id`, `addon_id`)
				VALUES
					('XenForo_Session', 'Brivium_VisitorsCounter_Session', 'load_class', 'Brivium_VisitorsCounter');
			";
		}else{
			$query[] = "
				DELETE FROM `xf_brivium_addon` WHERE `addon_id` = 'Brivium_VisitorsCounter';
			";
		}
		return $query;
	}
}

?>