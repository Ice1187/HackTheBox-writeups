HTTP/1.1 200 OK

Server: nginx/1.14.1

Date: Sat, 10 Jul 2021 00:09:07 GMT

Content-Type: text/html; charset=UTF-8

Connection: close

X-Powered-By: PHP/7.2.24

Content-Length: 1254



<?php
//    SeedDMS. Document Management System
//    Copyright (C) 2011-2015 Uwe Steinmann
//
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

/**
 * @uses SeedDMS_SQLiteFTS_Indexer
 */
require_once('SQLiteFTS/Indexer.php');

/**
 * @uses SeedDMS_SQLiteFTS_Search
 */
require_once('SQLiteFTS/Search.php');

/**
 * @uses SeedDMS_SQLiteFTS_Term
 */
require_once('SQLiteFTS/Term.php');

/**
 * @uses SeedDMS_SQLiteFTS_QueryHit
 */
require_once('SQLiteFTS/QueryHit.php');

/**
 * @uses SeedDMS_SQLiteFTS_IndexedDocument
 */
require_once('SQLiteFTS/IndexedDocument.php');

?>

