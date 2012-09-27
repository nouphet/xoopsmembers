<?php

class Xoopsmembers_UserGroupHandler extends XoopsObjectGenericHandler
{
	public $mTable = 'users';
	public $mPrimary = 'uid';
	public $mClass = 'XoopsUser';

	/**
	 * @param XoopsDatabase $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
		$this->mTable = $this->db->prefix($this->mTable);
	}

	/**
	 * @param Criteria $criteria
	 * @param null     $limit
	 * @param null     $start
	 * @param bool     $id_as_key
	 * @return array
	 */
	public function getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false)
	{
		$ret = array();

		$sql = 'SELECT u.* FROM `'.$this->mTable.'` AS u';
		$sql .= ' LEFT JOIN '.$this->db->prefix('groups_users_link').' AS g ON u.uid = g.uid';

		if ( $criteria !== null && is_a($criteria, 'CriteriaElement') )
		{
			$where = $this->_makeCriteria4sql($criteria);

			if ( trim($where) )
			{
				$sql .= ' WHERE '.$where;
			}

			$sql .= ' GROUP BY u.uid';

			$sorts = array();
			foreach ($criteria->getSorts() as $sort)
			{
				$sorts[] = '`'.$sort['sort'].'` '.$sort['order'];
			}
			if ( $criteria->getSort() != '' )
			{
				$sql .= ' ORDER BY '.implode(',', $sorts);
			}

			if ( $limit === null )
			{
				$limit = $criteria->getLimit();
			}

			if ( $start === null )
			{
				$start = $criteria->getStart();
			}
		}
		else
		{
			if ( $limit === null )
			{
				$limit = 0;
			}

			if ( $start === null )
			{
				$start = 0;
			}
		}

		$db = $this->db;
		$result = $db->query($sql, $limit, $start);

		if ( !$result )
		{
			return $ret;
		}

		while ( $row = $db->fetchArray($result) )
		{
			$obj = new $this->mClass();
			$obj->mDirname = $this->getDirname();
			$obj->assignVars($row);
			$obj->unsetNew();

			if ( $id_as_key )
			{
				$ret[$obj->get($this->mPrimary)] =& $obj;
			}
			else
			{
				$ret[] =& $obj;
			}

			unset( $obj );
		}

		return $ret;
	}

	public function getCount($criteria = null)
	{
		$sql = "SELECT COUNT(DISTINCT u.uid) AS c FROM `".$this->mTable.'` AS u';
		$sql .= ' LEFT JOIN '.$this->db->prefix('groups_users_link').' AS g ON u.uid = g.uid';

		if ( $criteria !== null && is_a($criteria, 'CriteriaElement') )
		{
			$where = $this->_makeCriteria4sql($criteria);

			if ( $where )
			{
				$sql .= " WHERE ".$where;
			}
		}

		return $this->_getCount($sql);
	}
}