<?php

class Andy_TodaysBirthdays_Listener
{
	public static function Forum($class, array &$extend)
	{
		$extend[] = 'Andy_TodaysBirthdays_ControllerPublic_Forum';
	}	
}