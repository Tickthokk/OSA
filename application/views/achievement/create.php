<div class = 'page-header'>
	<h1>Create Achievement for <em><?php echo $game_name; ?></em></h1>
</div>

<?php echo validation_errors('<div class = "alert alert-error">', '</div>'); ?>

<?php echo form_open('create/achievement/{game_id}', array('class' => 'form-horizontal')); ?>
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
		<!-- Description -->
		<div class = 'control-group'>
			<label class = 'control-label' for = 'description'>
				Description /<br />
				Requirements
			</label>
			<div class = 'controls'>
				<textarea name = 'description' id = 'description' class = 'input-xlarge'><?php echo set_value('description'); ?></textarea>
				<p class = 'help-block'>
					<em>We use a restricted version of <a href = '#' id = 'markdown_popup' title = 'Restricted Markdown Example'>Markdown</a>.</em>
				</p>
				<?php include APPPATH . 'views/common/markdown_example.php'; ?>
			</div>
		</div>
		<!-- System Exclusive -->
		<div class = 'control-group'>
			<label class = 'control-label'>
				System <br />
				Exclusive
			</label>
			<div class = 'controls'>
				<label class = 'checkbox'>
					<input type = 'checkbox' name = 'system_exclusive_yes' value = '1' <?php echo set_checkbox('system_exclusive_yes', '1'); ?> /> Yes, this achievement applies to only one system
				</label>
			</div>
		</div>
		<div class = 'control-group hide' id = 'system_exclusive'>
			<?php foreach ($systems as $s) : ?>
			<div class = 'controls'>
				<label class = 'radio'>
					<input type = 'radio' name = 'system_exclusive' id = 'system_exclusive' value = '<?php echo $s['id']; ?>' <?php echo set_radio('system_exclusive', $s['id']); ?> /> <?php echo strtoupper($s['slug']); ?>
				</label>
			</div>
			<?php endforeach; ?>
			<!--<div>
				<span class = 'btn btn-small'>System Not Listed?</span>
			</div>-->
		</div>	
		<!-- Description -->
		<div class = 'control-group'>
			<label class = 'control-label' for = 'description'>
				Icon
			</label>
			<div class = 'controls'>
				<select id = 'icon-select' name = 'icon'>
					<?php foreach ($map as $title => $sub) : ?>
					<optgroup label = '<?php echo $title; ?>'>
						<?php foreach ($sub as $img) : ?>
						<option value = '<?php echo $title . '/' . $img; ?>' title = '<?php echo $img; ?>' rel = 'popover'><?php echo $img; ?></option>
						<?php endforeach; ?>
					</optgroup>
					<?php endforeach; ?>
				</select>
				<div>
					<img src = '/assets/images/icons/' class = 'achievement-icon'>
				</div>
			</div>
		</div>
		<!-- Tags -->
		<legend>Tags</legend>
		<div class = 'control-group'>
			<label class = 'control-label' for = 'tags'>
				
			</label>
			<div class = 'controls'>
				<input type = 'text' name = 'tags' id = 'tags' value = '<?php echo set_value('tags'); ?>' />
				<p class = 'help-block'>
					Create your own, or use our Suggested Tags:
				</p>
				<ul id = 'default_tags' class = 'tagit ui-widget ui-widget-content ui-corner-all'>
					<?php 
						$default_tag_names = array();
						foreach ($default_tags as $tag) : 
							$default_tag_names[] = strtolower($tag['name']);
					?>
					<li class = 'tagit-choice ui-widget-content ui-state-default ui-corner-all' data-id = '<?php echo $tag['id']; ?>'>
						<span class = 'tagit-label'><?php echo strtolower($tag['name']); ?></span>
					</li>
					<?php endforeach; ?>
				</ul>

				<script type = 'text/javascript'>
					var default_tag_names = ['<?php echo implode("','", $default_tag_names); ?>']
				</script>
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