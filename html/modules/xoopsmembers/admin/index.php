<?php

require '../../../mainfile.php' ;
include_once XOOPS_ROOT_PATH.'/include/cp_header.php';

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
$mydirpath = dirname( dirname( __FILE__ ) ) ;

xoops_cp_header();
include dirname(__FILE__).'/menu.php' ;
echo '<h3>'.$xoopsModule->getVar('name').'</h3>' ;
xoops_cp_footer();

?>