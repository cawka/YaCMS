<?php

class AuthDBHelper extends AuthHelper
{
	protected function getRights( $group )
	{
		global $DB;

		$rows=APC_GetRows( array("user_rights",$group),$DB,
				"SELECT * FROM user_rights ".
					"WHERE user_group_id".(isset($group)?"='$group'":" IS NULL"),
				0);

		$ret=array();
		foreach( $rows as &$data )
		{
			$ret[$data['controller_id']][$data['allow_action']]=true;
		}

		return $ret;
	}
}
