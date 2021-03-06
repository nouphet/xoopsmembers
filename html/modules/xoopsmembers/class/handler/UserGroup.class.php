<?php

class Xoopsmembers_UserGroup extends XoopsUser
{
	/** @var null|XoopsSimpleObject */
	protected $profile = null;

	/**
	 * Return avatar URL
	 * @return string
	 */
	public function getAvatarUrl()
	{
		if ( $this->get('user_avatar') != "blank.gif" and file_exists(XOOPS_UPLOAD_PATH . "/" . $this->get('user_avatar')) )
		{
			return XOOPS_UPLOAD_URL . "/" . $this->getShow('user_avatar');
		}
		else
		{
			return XOOPS_URL . "/modules/user/images/no_avatar.gif";
		}
	}

	public function get($key)
	{
		if ( isset($this->vars[$key]) === true ) {
			return parent::get($key);
		}

		return $this->_getProfile($key);
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	protected function _getProfile($key)
	{
		if ( $this->profile === null ) {
			XCube_DelegateUtils::call('Legacy_Profile.GetProfile', new XCube_Ref($this->profile), $this->get('uid'));
		}

		if ( $this->profile instanceof XoopsSimpleObject ) {
			return $this->profile->get($key);
		}

		return null;
	}
}

class Xoopsmembers_UserGroupHandler extends XoopsObjectGenericHandler
{
	public $mTable = 'users';
	public $mPrimary = 'uid';
	public $mClass = 'Xoopsmembers_UserGroup';

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