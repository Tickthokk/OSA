<div id = 'markup_example' class = 'hide'>
	<?php $markdown_example = "_italics_,  **bold**, `code()` \n### and \n\r * any \n\r - of \n\r + these \n\r 7. One Banana \n\r 12. Two Banana \n\r 37. Three Banana"; ?>
	<h1>Input:</h1>

	<p>
		<?php echo preg_replace("/\n/", "<br>\n", $markdown_example); ?>
	</p>

	<hr>

	<h1>Output:</h1>

	<?php echo markdown($markdown_example); ?>
</div>