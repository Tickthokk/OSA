<div class = 'page-header'>
	<h1>Create Achievement for <em><?php echo $game->name; ?></em></h1>
</div>

<?php echo validation_errors('<div class = "alert alert-error">', '</div>'); ?>

<?php echo form_open('create/game', array('class' => 'well form-horizontal')); ?>
	<fieldset>
		<legend>General Achievement Information</legend>
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
		<!-- Rating -->
		<div class = 'control-group'>
			<label class = 'control-label' for = 'slug'>
				Rating
			</label>
			<div class = 'controls'>
				
			</div>
		</div>
	</fieldset>
	<fieldset>
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