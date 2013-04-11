<div class='icon_chooser'>
	<h1>Icon Chooser</h1>

	<hr>

	<section id='icons_step_1' class='hidden'>
		<h2 class='flr'>Step 1</h2>

		<h1>Tags</h1>

		<hr>

		<div class='icon_tags'>
			<?php foreach ($tags as $tag => $count) : ?>
			<span class='icon_tag'><?php echo ucwords($tag); ?> (<?php echo $count; ?>)</span>
			<?php endforeach; ?>
		</div>

		<hr>

		<h1>Some Random Icons</h1>

		<hr>

		<div class='icon_holder'>
			<?php
				shuffle($icons); // Shuffling will prevent dupes (instead of using array_rand())
				foreach (range(1,13) as $i) :
			?>
			<img src='/assets/images/icons/<?php echo $icons[$i]['filename']; ?>.svg' class='achievement_icon' data-id='<?php echo $icons[$i]['id']; ?>' data-filename='<?php echo $icons[$i]['filename']; ?>' data-tags='<?php echo $icons[$i]['tags']; ?>'>
			<?php endforeach; ?>
		</div>
	</section>

	<section id='icons_step_2' class='hiddenx'>
		<div class='row-fluid'>
			<div class='span8'>
				<h1><span id='tag_count'>83</span> "<span id='tag_name'>animal</span>" icons</h1>

				<hr>
				
				<div class='icon_holder'>
					<?php
						shuffle($icons); // Shuffling will prevent dupes (instead of using array_rand())
						foreach (range(1,33) as $i) :
					?>
					<img src='/assets/images/icons/<?php echo $icons[$i]['filename']; ?>.svg' class='achievement_icon bigger' data-id='<?php echo $icons[$i]['id']; ?>' data-filename='<?php echo $icons[$i]['filename']; ?>' data-tags='<?php echo $icons[$i]['tags']; ?>'>
					<?php endforeach; ?>
				</div>
			</div>
			<div class='span4'>
				<h1>Related Tags</h1>

				<hr>

				<div class='icon_tags'>
					<?php foreach ($tags as $tag => $count) : ?>
					<span class='icon_tag'><?php echo ucwords($tag); ?> (<?php echo $count; ?>)</span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section id='icons_step_3' class='hidden'>
		<div class='row-fluid'>
			<div class='span7'>
				<h1>anvil</h1>

				<hr>

				<img src='/assets/images/icons/ace.svg' class='achievement_icon super'>
			</div>
			<div class='span5'>
				<h1>Options</h1>

				<hr>

				<div class='form-horizontal'>
					<div class='control-group'>
						<label class='control-label'>
							Main Color
						</label>
						<div class='controls'>
							<input type='text'>
						</div>
					</div>
					<div class='control-group'>
						<label class='control-label'>
							Background Color
						</label>
						<div class='controls'>
							<input type='text'>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class='form-actions'>
			<button class='btn btn-large'>Cancel</button>
			<button class='btn btn-primary btn-large'>Use This Icon</button>
		</div>
	</section>
</div>