<?php
    $serverName = "localhost";
	$userName = "root";
	$userPassword = "";
	$dbName = "cwie_lru";

	$conn = new mysqli($serverName,$userName,$userPassword,$dbName);
    $conn -> set_charset("utf8");
