<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/07/14
 * Time: 17.12
 */

require_once "../../lib/start.php";

check_session();

$module_code = $_REQUEST['module'];

$sel_module = "SELECT * FROM rb_modules WHERE code_name = '{$module_code}'";
$res_module = $db->execute($sel_module);
$module = $res_module->fetch_assoc();

$_SESSION['__modules__'][$module_code]['home'] = $module['home'];
$_SESSION['__modules__'][$module_code]['lib_home'] = $module['lib_home'];
$_SESSION['__modules__'][$module_code]['front_page'] = $module['front_page'];
$_SESSION['__modules__'][$module_code]['path_to_root'] = $module['path_to_root'];

$_SESSION['__mod_area__'] = $_REQUEST['area'];

$uid = $_SESSION['__user__']->getUid();

if (isset($_REQUEST['page'])){
	header("Location: {$_REQUEST['page']}.php");
}
else {
	header("Location: {$module['front_page']}");
}
