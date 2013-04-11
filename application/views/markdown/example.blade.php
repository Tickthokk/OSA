<div id = 'markup_example' class = 'hide'>
	<?php $markdown_example = "_italics_,  **bold**, `code()`\n\r * any \n\r - of \n\r + these \n\r 7. One Banana \n\r 12. Two Banana \n\r 37. Three Banana \n# Header\n## Header\n### Header\n###### Header"; ?>

	<div class='row-fluid'>
		<div class='span6'>
			<h2 class='ignore'>Input:</h2>

			<p>
				{{ preg_replace("/\n/", "<br>\n", $markdown_example) }}
			</p>
		</div>
		<div class='span6'>
			<h2 class='ignore'>Output:</h2>

			{{ Sparkdown\Markdown($markdown_example) }}
		</div>
	</div>
</div>