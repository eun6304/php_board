<?php
include '../../lib/classes/DB/dbconfig.php';
include '../../lib/classes/member.php';

$mem = new Member($db);
$mem->logout();

?>