<?php

require dirname(__FILE__).'/../../mainfile.php';

require_once XOOPS_ROOT_PATH . "/core/XCube_PageNavigator.class.php";

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

$userGroupHandler = xoops_getmodulehandler('UserGroup');
$total = $userGroupHandler->getCount($criteria);

$pageNavigator = new XCube_PageNavigator('./index.php');
$pageNavigator->setPerpage($xoopsModuleConfig['users_per_page']);
$pageNavigator->setTotalItems($total);
$pageNavigator->fetch();

$criteria->setStart($pageNavigator->getStart());
$criteria->setLimit($pageNavigator->getPerpage());

/** @var $userGroupHandler Xoopsmembers_UserGroupHandler */
$xoopsTpl->assign('total', $total);
$xoopsTpl->assign('users', $userGroupHandler->getObjects($criteria));
$xoopsTpl->assign('pageNavigator', $pageNavigator);

require_once XOOPS_ROOT_PATH."/header.php";
$xoopsOption['template_main'] = 'xoopsmembers_index.html';
require_once XOOPS_ROOT_PATH."/footer.php";
