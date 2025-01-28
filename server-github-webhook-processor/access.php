<?php
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers:*");
  header("Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, TRACE, PATCH");
  header("HTTP/1.1 200 OK");
  die();
}

if (!headers_sent()) {
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers:*");
  header("Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, TRACE, PATCH");
}


date_default_timezone_set("Africa/Lagos");