<section>
	<header>
		<h1>Flag #<?php echo $flag_id; ?></h1>
	</header>
	<div class = 'form form-horizontal' >
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'help-block'>
					<p>First Letter will be automatically updated based on the Name</p>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Section</label>
					<div class = 'controls'>
						<?php echo $section_name; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Submitted By</label>
					<div class = 'controls'>
						<?php if ($flagged_by) : ?>
						<a href = '/user/<?php echo $flagger_username; ?>'><?php echo $flagger_username; ?></a>
						<?php else : ?>
						<?php echo $flagger_username; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Submitted On</label>
					<div class = 'controls'>
						<?php echo $flagged_on; ?>
					</div>
				</div>
				<?php if ($solved_by) : ?>
				<div class = 'control-group'>
					<label class = 'control-label'>Resolved By</label>
					<div class = 'controls'>
						<a href = '/user/<?php echo $solver_username; ?>'><?php echo $solver_username; ?></a>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Resolved On</label>
					<div class = 'controls'>
						<?php echo $solved_on; ?>
					</div>
				</div>
				<?php else : ?>
				<div class = 'control-group'>
					<label class = 'control-label'>Resolved?</label>
					<div class = 'controls'>
						No
					</div>
				</div>
				<?php endif; ?>
				<div class = 'control-group'>
					<label class = 'control-label'>Reason Given</label>
					<div class = 'controls'>
						<?php echo $reason; ?>
					</div>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<?php if ($solved_by) : ?>
			<a href = '/admin/flags/resolve/no/<?php echo $flag_id; ?>?nav=<?php echo $left_nav; ?>' class = 'btn btn-danger'>Mark Unresolved</a>
			<?php else : ?>
			<a href = '/admin/flags/resolve/yes/<?php echo $flag_id; ?>?nav=<?php echo $left_nav; ?>' class = 'btn btn-primary'>Mark Resolved</a>
			<?php endif; ?>
			<a href = '<?php echo $referer ?: '/admin/dashboard'; ?>' class = 'btn'>Nevermind</a>
		</div>
	</div>
</section>