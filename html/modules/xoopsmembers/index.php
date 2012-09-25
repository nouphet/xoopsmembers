<?php

require dirname(__FILE__).'/../../mainfile.php';

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('level', 0, '>'));
$criteria->addSort('uname', 'ASC');

/** @var $memberHandler XoopsMemberHandler */
$memberHandler = xoops_gethandler('member');
$xoopsTpl->assign('total', $memberHandler->getUserCount($criteria));
$xoopsTpl->assign('users', $memberHandler->getUsers($criteria, true));

require_once XOOPS_ROOT_PATH."/header.php";
$xoopsOption['template_main'] = 'xoopsmembers_searchresults.html';
require_once XOOPS_ROOT_PATH."/footer.php";
