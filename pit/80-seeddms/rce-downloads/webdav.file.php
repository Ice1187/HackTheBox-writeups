HTTP/1.1 200 OK

Server: nginx/1.14.1

Date: Sat, 10 Jul 2021 00:08:06 GMT

Content-Type: text/html; charset=UTF-8

Connection: close

X-Powered-By: PHP/7.2.24

Content-Length: 247



<?php // $Id$

	ini_set("include_path", ini_get("include_path").":/usr/local/apache/htdocs");
  require_once "HTTP/WebDAV/Server/Filesystem.php";
	$server = new HTTP_WebDAV_Server_Filesystem();
	$server->ServeRequest($_SERVER["DOCUMENT_ROOT"]);
?>
