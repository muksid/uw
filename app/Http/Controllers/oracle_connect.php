<?php
$db= "(DESCRIPTION =
				(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.6)(PORT = 1521))
				(CONNECT_DATA =
				(SERVER = DEDICATED)
				(SERVICE_NAME = IABS)
				)
				)";

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;
$result = array();

$conn = oci_connect('ibs', 'q2w3e4r', $db, 'AL32UTF8');
if (!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
