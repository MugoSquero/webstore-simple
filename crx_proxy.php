<?php

if (isset($_GET['extid'])) {
    $extensionID = $_GET['extid'];
} else {
    echo "An error occured, extensionID is missing.";
    exit(0);
}

require "misc/tools.php";

$url = $_REQUEST["url"];
$allowed_domains = array("clients2.google.com", "google.com");

if (in_array(get_root_domain($url), $allowed_domains))
{
  $file_content = request($url);
  header("Content-Disposition: attachment; filename=\"" . $extensionID . ".crx" . "\"");
  header("Content-Type: application/octet-stream");
  header("Content-Length: " . strlen($file_content));
  header("Connection: close");
  echo $file_content;
}

?>