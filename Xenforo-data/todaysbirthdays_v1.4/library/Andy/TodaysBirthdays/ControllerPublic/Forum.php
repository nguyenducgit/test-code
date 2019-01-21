<?php

class Andy_TodaysBirthdays_ControllerPublic_Forum extends XFCP_Andy_TodaysBirthdays_ControllerPublic_Forum
{
	public function actionIndex()
	{
		//########################################
		// Add $birthdays to viewParams in order
		// to show Today's Birthdays block in
		// forum_list sidebar.
		//########################################		

		// get parent		
		$parent = parent::actionIndex();
		
		// return parent action if this is a redirect or other non View response
		if (!$parent instanceOf XenForo_ControllerResponse_View)
		{
			return $parent;
		}
		
		// return if rss
		if ($this->_routeMatch->getResponseType() == 'rss')	
		{
			return $parent;
		}
		
		// prepare viewParams
		if ($parent instanceOf XenForo_ControllerResponse_View)
		{
			// get options from Admin CP -> Options -> Todays Birthdays -> Last Activity
			$cutoffDays = XenForo_Application::get('options')->todaysBirthdaysCutoffDays;
			
			// don't show birthdays of members who have a last activity greater than this
			$timestamp = time() - ($cutoffDays * 86400);		
			
			//########################################
			// code reference:
			// library/XenForo/ControllerPublic/Member.php
			//########################################
			
			$userModel = $this->getModelFromCache('XenForo_Model_User');
			
			$visitor = XenForo_Visitor::getInstance();
			$dt = new DateTime('now', new DateTimeZone($visitor['timezone']));
	
			list($month, $day) = explode('/', $dt->format('n/j'));
	
			$criteria = array(
				'user_state' => 'valid',
				'is_banned' => 0,
				'last_activity' => array('>',$timestamp)
			);
	
			$birthdays = $userModel->getBirthdayUsers($month, $day, $criteria, array(
				'join' => XenForo_Model_User::FETCH_USER_FULL,
				'limit' => 20
			));

			// add to viewParams
			$viewParams = array(
			'birthdays' => $birthdays					
			);
			
			// add viewParams to parent params
			$parent->params += $viewParams;
		}	
		
		// return parent
		return $parent;	
	}
}