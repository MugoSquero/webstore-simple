<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" type="text/css" href="misc/footer.css">
    <title>Web Store Simple</title>
</head>
<body>
	<div class="content">
		<div class="container">
			<h1>Web Store Simple</h1>
			<form method="get" action="search.php">
				<label for="searchQuery">Search Chrome Extensions:</label>
				<input type="text" id="searchQuery" name="query">
				
				<!-- Dropdown select for Chromium version -->
				<label for="chromiumVersion">Select Chromium Version:</label>
				<select id="chromiumVersion" name="version">
					<!-- Generate options from version 1 to 119 -->
					<?php
						for ($i = 119; $i >= 1; $i--) {
							echo "<option value='$i'>$i</option>";
						}
					?>
				</select>
				
				<button type="submit">Search</button>
			</form>
		</div>
	</div>
<?php include 'misc/footer.html'; ?>
</body>
</html>
