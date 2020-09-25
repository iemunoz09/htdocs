<?php

echo('	<link rel="stylesheet" type="text/css" href="/css/bootstrap 4.5/bootstrap.min.css" >
		<link rel="stylesheet" type="text/css" href="/css/appSite.css">');

if (basename($_SERVER['PHP_SELF']) == 'teamView.php') //* Returns The Current PHP File Name */
{
	echo( '<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="/css/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="/css/select.dataTables.min.css">');
};
?>