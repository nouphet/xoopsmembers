<?php

require dirname(__FILE__).'/../../mainfile.php';

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('level', 0, '>'));

// We can not use IN statement here,
// because XoopsObjectGenericHandler doesn't support a conditon which is not a property of an XoopsObject
$subcriteria = new CriteriaCompo();

foreach ( $xoopsModuleConfig['listed_groups'] as $groupId )
{
	$subcriteria->add(new Criteria('g.groupid', $groupId), 'OR');
}

$criteria->add($subcriteria);
$criteria->addSort('uname', 'ASC');

/** @var $userGroupHandler Xoopsmembers_UserGroupHandler */
$userGroupHandler = xoops_getmodulehandler('UserGroup');
$xoopsTpl->assign('total', $userGroupHandler->getCount($criteria));
$xoopsTpl->assign('users', $userGroupHandler->getObjects($criteria));

require_once XOOPS_ROOT_PATH."/header.php";
$xoopsOption['template_main'] = 'xoopsmembers_index.html';
require_once XOOPS_ROOT_PATH."/footer.php";
