<?php
if (isset($_GET['extensionID'])) {
    $extensionID = $_GET['extensionID'];
    $url = "https://chrome.google.com/webstore/ajax/detail?hl=en-US&gl=JA&pv=20210820&id=" . urlencode($extensionID);
} else {
    echo "An error occured, extensionID is missing.";
    exit(0);
}
if (isset($_GET['version']) && !($_GET['version'] > 118)) {
    $chromiumVersion = $_GET['version'];
} else {
    echo "Please select a valid chromium version.";
    exit(0);
}
$data = "login=&";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$response = json_decode(substr($response, 5), true);

if (isset($response[1][1])) {
    $details = $response[1][1];
} else {
    echo "The extension ID is wrong.";
    exit(0);
}

?>
<?php
    $name = $details[0][1];
    $author = $details[0][2];
    $iconUrl = $details[0][4] ?? $details[0][3];
    $description = $details[0][6];
    $category = $details[0][10];
    $rating = $details[0][12];
    $rateCount = $details[0][22];
    $installCount = $details[0][23];
    $overview = str_replace("\n", "<br>", $details[1]);
    $website = !empty($details[3]) ? $details[3] : "Website is not provided.";
    $supportURI = !empty($details[5]) ? $details[5] : "Support URI is not provided.";
    $version = $details[6];
    $updateDate = $details[7];
    $languages = $details[8];
    $languagesList = implode(', ', $languages);
    $languagesCount = count($languages);
    $manifest = json_decode($details[9][0], true);
    // TODO: [11][0-n][17]
    $images = $details[11];
    $size = $details[25];
    $contact = !empty($details[35][0]) ? "mailto:" . $details[35][0] : "Contact information is not provided.";
    $privacyPolicy = !empty($details[35][2]) ? $details[35][2] : "Privacy Policy is not provided.";

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $name; ?> - Web Store Simple</title>
    <link rel="stylesheet" type="text/css" href="details.css">
    <link rel="stylesheet" type="text/css" href="misc/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php include 'misc/header.html'; ?>
<div class="detail-container">
<?php
    echo "<ul class=\"extension-pane\">";
    echo "<li class=\"extension\">";
    if (isset($details[4])) {
        echo "<div class=\"extension-image-container\">";
        echo "<img class=\"extension-image\" src=\"/image_proxy.php?url=" . urlencode($iconUrl) . "\" alt=\"$name\">";
        echo "</div>";
    } elseif (isset($details[3])) {
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
    echo "<p class=\"extension-overview\"><b>Overview</b>: $overview</p>";?>
    <p class="extension-website"><b>Website</b>: <?php echo !empty($details[3]) ? "<a href='$website' target='_blank'>$website</a>" : $website; ?></p>
    <p class="extension-support-uri"><b>Support URI</b>: <?php echo !empty($details[5]) ? "<a href='$supportURI' target='_blank'>$supportURI</a>" : $supportURI; ?></p><?php
    echo "<p class=\"extension-version\"><b>Version</b>: $version</p>";
    echo "<p class=\"extension-update-date\"><b>Update Date</b>: $updateDate</p>";
    echo "<div class=\"extension-languages\">";
    echo "<p>Languages: <span class=\"language-dropdown\">";
    echo "<span class=\"languages-count\">$languagesCount</span>";
    echo "<span class=\"language-list\">$languagesList</span>";
    echo "</span></p>";
    echo "</div>";
    echo "<p class=\"extension-size\"><b>Size</b>: $size</p>";?>
    <p class="extension-contact"><b>Contact</b>: <?php echo !empty($details[35][0]) ? "<a href='$contact' target='_blank'>$contact</a>" : $contact; ?></p>
    <p class="extension-privacy-policy"><b>Privacy Policy</b>: <?php echo !empty($details[35][2]) ? "<a href='$privacyPolicy' target='_blank'>$privacyPolicy</a>" : $privacyPolicy; ?></p><?php
    echo "<a class=\"install-button\" href=\"/crx_proxy.php?url=" .  urlencode("https://clients2.google.com/service/update2/crx?response=redirect&acceptformat=crx2,crx3&prodversion=$chromiumVersion&x=id%3D$extensionID%26installsource%3Dondemand%26uc") . "&extid=" . urlencode($extensionID) . "\">Install</a>";

    echo "</div>";
    echo "</li>";
    echo "</ul>";

// Close the cURL session
curl_close($ch);
?>
</div>

<div class="collage-container">
    <?php
    $images = $details[11];
        foreach ($images as $index => $image) {
			if (!empty($image[17]) || isset(($image[17]))) {
            echo '<div class="image">';
            echo '<img src="/image_proxy.php?url=' . urlencode($image[17]) . '" alt="" width="640" height="400">';
            echo '</div>';
		}
    }
    ?>
</div>

<?php include 'misc/footer.html'; ?>
</body>
</html>
