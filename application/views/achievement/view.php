<style>
	#tags span { cursor: pointer; margin: 5px; position: relative; line-height: 30px;}
	
	.tags-wrapper { margin-top: 20px; position: relative; }
	.tags-wrapper .help-block { margin-top: 10px; }
	.tag-vote-group { position: absolute; z-index: 2; }
	.tags-wrapper [rel=tooltip] { position: absolute; top: 8px; right: 8px; }
</style>

<script type = 'text/javascript'>
	var achievement_id = {achievement_id};
	var game_id = <?php echo $game['id']; ?>;
</script>

<div class = 'page-header'>
	<?php if ($achievement['systemExclusive']) : ?>
	<div id = 'system-exclusive' class = 'label label-inverse'>
		<div>
			<?php echo strtoupper($achievement['systemExclusive']['slug']); ?>
			<div>Exclusive</div>
		</div>
	</div>
	<?php endif; ?>

	<h1><a href = '/game/<?php echo $game['id']; ?>'><?php echo $game['name']; ?></a> - <?php echo $achievement['name']; ?></h1>
</div>

<div class = 'row'>
	<div class = 'span4'>
		<div class = 'center'>
			<img src = '/images/game/<?php echo $game['id']; ?>/300' />
		</div>

		<div class = 'well tags-wrapper'>
			<i class = 'icon-question-sign' rel = 'tooltip' title = 'Click on a tag to vote on its inclusion!' style = 'float: right;'></i>
			<div id = 'tags'>
				<?php include '_tags.php'; ?>
			</div>
		</div>

		<div class = 'well hide input-append' id = 'suggest-tag' style = 'margin-top: 10px;'>
			<h4>
				<i class = 'icon-tags'></i> Suggest New Tag
			</h4>
			<input type = 'text' />
			<span class = 'btn submit' title = 'Submit'><i class = 'icon-ok'></i></span>
			<span class = 'btn cancel' title = 'Cancel'><i class = 'icon-remove'></i></span>
		</div>

		<div class = 'well'>
			<span class = 'btn btn-primary flr suggest-new-tag'>
				<i class = 'icon-tag icon-white'></i>
				Suggest New Tag
			</span>
			<span class = 'btn btn-success<?php if ($user_has_achieved) echo ' hide'; ?>' id = 'i-did-it'>
				<i class = 'icon-certificate icon-white'></i>
				I did it!
			</span>
			<span class = 'btn btn-info<?php if ( ! $user_has_achieved) echo ' hide'; ?>' id = 'you-did-it'>
				<i class = 'icon-ok icon-white'></i>
				You did it!
			</span>
		</div>

		<div class = 'well' style = 'margin-top: 10px;'>
			<h2>Last 10 Achievers</h2>

			<div id = 'achievers'>
				<?php include '_achievers.php'; ?>
			</div>

			<p><span class = 'btn btn-info btn-small'>See all achievers!</span></p>
		</div>
	</div>
	<div class = 'span8'>
		<div class = 'well'>
			<?php if ($achievement['userId'] == $this->user->id) : ?>
			<div class = 'flr'>
				<span class = 'btn btn-warning edit_achievement' data-toggle = 'modal' data-target = 'achievement_editing'>
					<i class = 'icon-pencil icon-white'></i>
					Edit
				</span>
				<span class = 'btn btn-danger delete_achievement' data-toggle = 'modal' data-target = 'achievement_deleting'>
					<i class = 'icon-trash icon-white'></i>
					Delete
				</span>
			</div>
			<?php endif; ?>

			<h2>How To Achieve:</h2>
			<div class = 'description'>
				<?php if (empty($achievement['description'])) : ?>
				<p>
					<em>No Description</em>
				</p>
				<?php else : ?>
				<p>
					<?php echo markdown($achievement['description']); ?>
				</p>
				<?php endif; ?>
			</div>
			<p class = 'created-modified'>
				Created By: 
				<a href = '/user/profile/<?php echo $achievement['userId']; ?>#<?php echo $achievement['username']; ?>' title = 'View Profile'><?php echo $achievement['username']; ?></a>
				on <?php echo parse_sql_timestamp_full($achievement['added']); ?>
				<?php if ($achievement['added'] != $achievement['modified'] && (int) $achievement['modified']) : ?>
				<span>Modified: <?php echo parse_sql_timestamp_full($achievement['modified']); ?></span>
				<?php endif; ?>
			</p>
		</div>

		<div class = 'well' id = 'comments' data-top-comment-id = '<?php echo $top_comment_id; ?>' data-current-comment-count = '5'>
			<h2>Comments</h2>
			<?php if ( ! $total_comments) : ?>
			<div class = 'well no-comments'>
				<em>None!</em>  Be the first!
			</div>
			<?php else : ?>
			<?php include '_comments.php'; ?>
			<?php endif; ?>
		</div>

		<?php echo form_open('achievement/comment/{achievement_id}', array('class' => 'well form-horizontal')); ?>
			<fieldset>
				<legend>Leave a comment</legend>

				<div class = 'control-group'>
					<label class = 'control-label' for = 'description'>
						Comment
					</label>
					<div class = 'controls'>
						<textarea name = 'comment' class = 'input-xlarge' rows = '6'><?php echo $old_comment; ?></textarea>
						<p class="help-block">
							<em>We use a restricted version of <a href = '#' id = 'markdown_popup' data-original-title = 'Restricted Markdown Example'>Markdown</a>.</em>
						</p>
						<?php include APPPATH . 'views/common/markdown_example.php'; ?>
					</div>
				</div>

				<div class = 'controls'>
					<button class = 'btn btn-primary'>
						Submit
					</button>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<?php include '_view_modals.php'; ?>