<?php
class Brivium_VisitorsCounter_Model_VisitorsCounter extends XenForo_Model
{
	public function updateVisitorsCounter()
	{
		$db = $this->_getDb();
		$viewCounter = $this->getViewCounter();
		if(empty($viewCounter))
		{
			return false;
		}
		$timeStamps = self::getDayStartTimestamps();
		$visitorCount = $beginVisitorsCount= array(
			'today' => 0,
			'yesterday' => 0,
			'this_week' => 0,
			'last_week' => 0,
			'this_month' => 0,
			'last_month' => 0,
			'this_year' => 0,
			'last_year' => 0,
			'all_time' => 0,
		);
		foreach ($viewCounter as $time => $count)
		{
			foreach ($timeStamps as $type => $_time)
			{
				$beforeType = '';
				switch ($type) 
				{
					case 'today':
					case 'this_week':
					case 'this_month':
					case 'this_year':
					case 'all_time':
						if($time >= $_time)
						{
							$visitorCount[$type] +=$count;
						}
						break;
						
					case 'yesterday':
						if(empty($beforeType))
						{
							$beforeType = 'today';
						}
					case 'last_week':
						if(empty($beforeType))
						{
							$beforeType = 'this_week';
						}
					case 'last_month':
						if(empty($beforeType))
						{
							$beforeType = 'this_month';
						}
					case 'last_year':
						if(empty($beforeType))
						{
							$beforeType = 'this_year';
						}
						if(isset($visitorCount[$type]) && isset($visitorCount[$beforeType]) && $time >= $_time && $time < $timeStamps[$beforeType])
						{
							$visitorCount[$type] +=$count;
						}
						break;
				}
			}
		}
		$exitingVisitorsCount = $this->getVisitorsCounter();
		$exitingVisitorsTimestamps = $this->getVisitorsCounterAndTimestamps();

		$exitingVisitorsCount +=$beginVisitorsCount;
		$exitingVisitorsTimestamps += $beginVisitorsCount;

		$newVisitorsCount = $exitingVisitorsCount;
		
		foreach ($exitingVisitorsCount as $type => $exitingValue)
		{
			$beforeType = '';
			switch ($type) 
			{
				case 'yesterday':
					$beforeType = 'today'; break;
				case 'last_week':
					$beforeType = 'this_week'; break;
				case 'last_month':
					$beforeType = 'this_month'; break;
				case 'last_year':
					$beforeType = 'this_year'; break;
			}
			switch ($type) 
			{
				case 'today':
				case 'this_week':
				case 'this_month':
				case 'this_year':
					if(!empty($exitingVisitorsTimestamps[$type]) && $exitingVisitorsTimestamps[$type] == $timeStamps[$type])
					{
						$newVisitorsCount[$type] = $exitingValue + $visitorCount[$type];
					}else 
					{
						$newVisitorsCount[$type] = $visitorCount[$type];
					}
					break;
					
				case 'yesterday':
				case 'last_week':
				case 'last_month':
				case 'last_year':
					if(!empty($exitingVisitorsTimestamps[$type]) && $exitingVisitorsTimestamps[$type] == $timeStamps[$type])
					{
						$newVisitorsCount[$type] = $exitingValue + $visitorCount[$type];
					}elseif(isset($exitingVisitorsTimestamps[$beforeType]) && $exitingVisitorsTimestamps[$beforeType] == $timeStamps[$type])
					{
						$newVisitorsCount[$type] = $exitingVisitorsCount[$beforeType]+$visitorCount[$type];
					}else 
					{
						$newVisitorsCount[$type] = $visitorCount[$type];
					}
					break;
				case 'all_time':
					$newVisitorsCount[$type] = $exitingValue + $visitorCount[$type];
					break;
			}
		}
		
		foreach ($newVisitorsCount as $type => $count)
		{
			$displayOrder = 0;
			switch ($type) 
			{
				case 'today':
					$displayOrder = 10; break;
				case 'yesterday':
					$displayOrder = 20; break;
				case 'this_week':
					$displayOrder = 30; break;
				case 'last_week':
					$displayOrder = 40; break;
				case 'this_month':
					$displayOrder = 50; break;
				case 'this_month':
					$displayOrder = 60; break;
				case 'last_month':
					$displayOrder = 70; break;
				case 'this_year':
					$displayOrder = 80; break;
				case 'last_year':
					$displayOrder = 90; break;
				case 'all_time':
					$displayOrder = 100; break;
			}
			
			$values[] = sprintf('(%s,%s,%s,%s)', $db->quote($type), $db->quote($count), $db->quote($displayOrder), $db->quote($timeStamps[$type]));
		}
		
		if(!empty($values))
		{
			$valueString = implode(',', $values);
			$db->query('
				INSERT INTO `xf_brivium_visitors_counter`
					(`time_type`, `count`, `display_order`, `time_stamps`)
				VALUES
					'.$valueString.'
				ON DUPLICATE KEY UPDATE
					`count` = VALUES(`count`),
					`display_order` = VALUES(`display_order`),
					`time_stamps` = VALUES(`time_stamps`);
			');
			$db->query('TRUNCATE xf_brivium_visitors_view_date');
		}
	}
	
	public function getViewCounter()
	{
		return $this->_getDb()->fetchPairs('
			SELECT MAX(`view_date`), COUNT(`session_id`)
			FROM xf_brivium_visitors_view_date
			GROUP BY FROM_UNIXTIME(`view_date`, \'%Y-%m-%d\')
			ORDER BY `view_date` DESC
		');
	}
	
	public function getVisitorsCounterAndTimestamps()
	{
		return $this->_getDb()->fetchPairs('
			SELECT time_type, time_stamps
			FROM xf_brivium_visitors_counter
			ORDER BY display_order
		');
	}
	public function getVisitorsCounter()
	{
		return $this->_getDb()->fetchPairs('
			SELECT time_type, count
			FROM xf_brivium_visitors_counter
			ORDER BY display_order
		');
	}
	
	public function renderToTitleAndValue(array $visitorsCounter)
	{
		if(empty($visitorsCounter))
		{
			return array();
		}
		
		$newArray = array();
		foreach ($visitorsCounter as $type => $value)
		{
			$newArray[] = array(
				'title' => new XenForo_Phrase('BRVC_'.$type),
				'value' => $value
			);
		}
		
		return $newArray;
	}
	
	protected static $_dayStartTimestamps;
	public static function getDayStartTimestamps($time = NULL)
	{
		if (!self::$_dayStartTimestamps)
		{
			if($time === null)
			{
				$time = XenForo_Application::$time;
			}
			$date = new DateTime('@' . $time);
			$date->setTimezone(new DateTimeZone('UTC'));
			list($dayOfWeek, $dayOfMonth, $day, $month , $year) = explode('|', $date->format('w|j|d|m|Y'));
			
			$date->setTime(0, 0, 0);
			$today =  $date->format('U');
			
			$date->modify('-1 day');
			$yesterday = $date->format('U');
			$date->modify('1 day');
			
			$date->modify(sprintf('-%d day', $dayOfWeek));
			$this_week = $date->format('U');

			$date->modify(sprintf('-%d week', 1));
			$last_week = $date->format('U');
			$date->modify(sprintf('%d week', 1));
			$date->modify(sprintf('%d day', $dayOfWeek));
			
			$date->setDate($year, $month, 1);
			$this_month = $date->format('U');
			
			$date->modify(sprintf('-%d month', 1));
			$last_month = $date->format('U');
			
			$date->setDate($year, 1, 1);
			$this_year = $date->format('U');
			
			$date->modify(sprintf('-%d year', 1));
			$last_year = $date->format('U');
	
			self::$_dayStartTimestamps = array(
				'today' => $today,
				'yesterday' => $yesterday,
				'this_week' => $this_week,
				'last_week' => $last_week,
				'this_month' => $this_month,
				'last_month' => $last_month,
				'this_year'	=> $this_year,
				'last_year'	=> $last_year,
				'all_time' => 0
			);
		}
		return self::$_dayStartTimestamps;
	}
	
	public function importOriginalDatas()
	{
		$timeStamps = self::getDayStartTimestamps();
		
		$db = $this->_getDb();
		foreach ($timeStamps as $type => $time)
		{
			switch ($type) 
			{
				case 'today':
					$displayOrder = 10; break;
				case 'yesterday':
					$displayOrder = 20; break;
				case 'this_week':
					$displayOrder = 30; break;
				case 'last_week':
					$displayOrder = 40; break;
				case 'this_month':
					$displayOrder = 50; break;
				case 'this_month':
					$displayOrder = 60; break;
				case 'last_month':
					$displayOrder = 70; break;
				case 'this_year':
					$displayOrder = 80; break;
				case 'last_year':
					$displayOrder = 90; break;
				case 'all_time':
					$displayOrder = 100; break;
			}
			if(empty($displayOrder))
			{
				continue;
			}
			
			$db->query('
				INSERT INTO `xf_brivium_visitors_counter`
					(`time_type`, `count`, `display_order`, `time_stamps`)
				VALUES
					(?, ?, ?, ?)
			', array($type, 0, $displayOrder, $time));
		}
	}
	
	public function insertDataForTestOnDay($afterDay, $items, $inday = true)
	{
		$now = XenForo_Application::$time;
		$time = $now - $afterDay*86400;
		
		if($inday)
		{
			$date = new DateTime('@' . $time);
			$date->setTimezone(new DateTimeZone('UTC'));
			$date->setTime(0, 0, 0);
			$beginDay =  $date->format('U');
		}else 
		{
			$beginDay = $time-86400;
		}
		
		$betweenTime = 86400/$items;
		for ($i=$beginDay; $i<$beginDay+86400;)
		{
			$sessionId = md5(XenForo_Application::generateRandomString(16, true));
			$this->_getDb()->query( '
				INSERT IGNORE INTO xf_brivium_visitors_view_date
					(session_id, view_date)
				VALUES (?, ?)
			', array($sessionId, $i));
			$i += $betweenTime;
		}
		
	}
	public function insertDataForTest($day = 10, $itemPerDay = 2)
	{
		$now = XenForo_Application::$time;
		$miniTime = $now - $day*86400;
		$betweenTime = 86400/$itemPerDay;
		for ($time=$miniTime; $time<=$now;)
		{
			$sessionId = md5(XenForo_Application::generateRandomString(16, true));
			$this->_getDb()->query( '
				INSERT IGNORE INTO xf_brivium_visitors_view_date
					(session_id, view_date)
				VALUES (?, ?)
			', array($sessionId, $time));
			$time += $betweenTime;
		}
	}
}