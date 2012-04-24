<div class = 'page-header'>
	<h1>Create Game Entry</h1>
</div>

<?php echo validation_errors('<div class = "alert alert-error">', '</div>'); ?>

<?php echo form_open('create/game', array('class' => 'well form-horizontal')); ?>
	<fieldset>
		<legend>General Game Information</legend>
		<!-- Name -->
		<div class = 'control-group'>
			<label class = 'control-label' for = 'name'>
				Name
			</label>
			<div class = 'controls'>
				<input type = 'text' name = 'name' id = 'name' value = '<?php echo set_value('name'); ?>' size = '45' class = 'input-xlarge' />
			</div>
		</div>
		<!-- Slug -->
		<div class = 'control-group'>
			<label class = 'control-label' for = 'slug'>
				Slug
			</label>
			<div class = 'controls'>
				<input type = 'text' name = 'slug' id = 'slug' value = '<?php echo set_value('slug'); ?>' size = '45' class = 'input-xlarge' />
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Systems</legend>
		<div class = 'control-group'>
			<label class = 'control-label'>Console or Portable?</label>
			<div class = 'controls'>
				<label class = 'radio'>
					<input type = 'radio' name = 'c-or-p' id = 'consoles' value = 'c' <?php echo set_radio('c-or-p', 'c', TRUE); ?> /> 
					Console
				</label>
				<label class = 'radio'>
					<input type = 'radio' name = 'c-or-p' id = 'portables' value = 'p' <?php echo set_radio('c-or-p', 'p'); ?> /> 
					Portable
				</label>
			</div>
		</div>
		<?php foreach ($systems as $s) : ?>
		<div class = 'control-group systems'>
			<label class = 'control-label'>
				<?php echo ucfirst($s['slug']); ?>
			</label>
			<?php foreach (array('consoles', 'portables') as $type) : ?>
			<div class = 'controls system_type <?php echo $type; ?>'>
				<?php foreach ($s[$type] as $c) : ?>
				<label class = 'checkbox'>
					<input type = 'checkbox' name = 'system[]' value = '<?php echo $c['id']; ?>' <?php echo set_checkbox('system[]', $c['id']); ?> />
					<?php echo strtoupper($c['slug']); ?>
				</label>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
		<!-- Submit -->
		<div class = 'control-group'>
			<label class = 'control-label'>&nbsp;</label>
			<div class = 'controls'>
				<button type = 'submit' class = 'btn btn-primary'>
					<i class = 'icon-ok icon-white'></i>
					Create
				</button>
			</div>
		</div>
	</fieldset>
</form>