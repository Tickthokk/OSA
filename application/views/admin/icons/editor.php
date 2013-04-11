<section>
	<header>
		<h1>Icon Editor</h1>
	</header>
	<div class = 'row-fluid'>
		<div class='span6'>
			<div style='text-align:center;'>
				<div>
					{icon_dropdown}
				</div>
				<div id='icon_holder'>
					<img src="/assets/images/icons/ace.svg" class='svg achievement_icon editor' />
				</div>
			</div>
		</div>
		<div class='span6' id = 'editor_fields'>
			<fieldset>
				<div class='hidden'>
					<strong>Generated String</strong>
					<div id='generated'>
						<input class='input-xlarge'>
					</div>
					<br>
				</div>
				<div class = 'row-fluid'>
					<div class = 'span6'>
						<strong>Color</strong>
						<div style='width:120px;'>
							<input id='evol-color-main' class='input-small colorPicker evo-cp0' />
						</div>
					</div>
					<div class = 'span6'>
						<strong>Background</strong>
						<div style='width:120px;'>
							<input id='evol-color-bg' class='input-small colorPicker evo-cp1' />
						</div>
					</div>
				</div>
				<br>
				<a href='#' class='advanced'>Advanced</a>
				<br>
				<div id='advanced' class='hidden'>
					<div id='stroke'>
						<strong>Stroke Type</strong> <select>
							<option value=''> -- Default -- </option>
							<option value='m'>Miter</option>
							<option value='r'>Round</option>
							<option value='b'>Bevel</option>
						</select>
					</div>
					<br>
					<strong>Fill &amp; Stroke Colors</strong>
					<ol id='colors'></ol>
					<br>
					<strong>Replace All</strong>
					<ol id='replace-all'></ol>
				</div>
			</fieldset>
		</div>
	</div>
</section>