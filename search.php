<?php
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $url = "https://chrome.google.com/webstore/ajax/item?hl=en-US&gl=JA&pv=20210820&count=112&searchTerm=" . urlencode($searchQuery) . "&sortBy=0";
} else {
	echo "Please enter a search query.";
	exit(0);
}
if (isset($_GET['version']) && !($_GET['version'] > 119)) {
    $chromiumVersion = $_GET['version'];
} else {
	echo "Please select a valid chromium version.";
	exit(0);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search results - Web Store Simple</title>
    <link rel="stylesheet" type="text/css" href="search.css">
	<link rel="stylesheet" type="text/css" href="misc/footer.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include 'misc/header.html'; ?>
<?php

$data = "login=&";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$response = json_decode(substr($response, 5), true);

$extensions = $response[1][1];

if (!isset($extensions[1])) {
	echo "<h2 class=\"error\">An error occured: No results found.</h2>";
	include 'misc/footer.html';
	exit(0);
}

// Separate extensions by category
$extensionsByCategory = [];
foreach ($extensions as $extension) {
    $category = $extension[10];
    $extensionsByCategory[$category][] = $extension;
}

// Inside the HTML rendering of the extensions
foreach ($extensionsByCategory as $category => $categoryExtensions) {
    echo "<h2 class=\"extension-title\">$category</h2>";
    echo "<ul class=\"extension-list\">";
    foreach ($categoryExtensions as $extension) {
        $name = $extension[1];
        $author = $extension[2];
        $iconUrl = $extension[4] ?? $extension[3];
        $description = $extension[6];
        $rating = $extension[12];
        $installCount = $extension[23];
        $extensionID = $extension[0];

        echo "<li class=\"extension\">";
        if (isset($extension[4])) {
            echo "<div class=\"extension-image-container\">";
            echo "<img class=\"extension-image\" src=\"/image_proxy.php?url=" . urlencode($iconUrl) . "\" alt=\"$name\">";
            echo "</div>";
        } elseif (isset($extension[3])) {
            echo "<div class=\"extension-image-container\">";
            echo "<img class=\"extension-image\" src=\"/image_proxy.php?url=" . urlencode($iconUrl) . "\" alt=\"$name\">";
            echo "</div>";
        }
        echo "<div class=\"extension-info\">";
        echo "<p class=\"extension-name\">$name</p>";
        echo "<p class=\"extension-author\"><b>Author</b>: $author</p>";
        echo "<p class=\"extension-description\">$description</p>";
        echo "<p class=\"extension-rating\"><b>Rating</b>: $rating</p>";
        echo "<p class=\"extension-install-count\"><b>Install Count</b>: $installCount</p>";
        echo "<p class=\"extension-id\"><b>Extension ID</b>: $extensionID</p>";
		echo "<div class=\"button-container\">";
		echo "<a class=\"details-button\" href=\"/details.php?extensionID=" . urlencode($extensionID) . "&version=" . urlencode($chromiumVersion) . "\">Details</a>";
        echo "<a class=\"install-button\" href=\"/crx_proxy.php?url=" .  urlencode("https://clients2.google.com/service/update2/crx?response=redirect&acceptformat=crx2,crx3&prodversion=$chromiumVersion&x=id%3D$extensionID%26installsource%3Dondemand%26uc") . "&extid=" . urlencode($extensionID) . "\">Install</a>";
        echo "</div>";
        echo "</div>";
        echo "</li>";
    }
    echo "</ul>";
}

// Close the cURL session
curl_close($ch);
?>
<?php include 'misc/footer.html'; ?>
</body>
</html>
