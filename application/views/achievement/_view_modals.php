
<div id = 'tag_vote' class = 'hide'>
	<div class = 'btn-group tag-vote-group' data-toggle = 'buttons-radio'>
		<span class = 'btn btn-mini keep' title = 'Keep it!'>
			<i class = 'icon-thumbs-up'></i>
		</span>
		<span class = 'btn btn-mini drop' title = 'Drop it.'>
			<i class = 'icon-thumbs-down'></i>
		</span>
		<span class = 'btn btn-mini flag' title = 'Flag as Inappropriate!'>
			<i class = 'icon-warning-sign'></i>
		</span>
	</div>
</div>

<div id = 'achievement_editing' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Edit the Achievement</h3>
	</div>
	<div class = 'modal-body'>
		<textarea><?php echo $achievement['description']; ?></textarea>
	</div>
	<div class = 'modal-footer'>
		<span class = 'fll'><em>Please don't change the original intent of the achievement!</em></span>
		<span class = 'btn' data-dismiss = 'modal'>Close</span>
		<span class = 'btn btn-primary save'>Save Changes</span>
	</div>
</div>

<div id = 'comment_editing' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Edit Your Comment</h3>
	</div>
	<div class = 'modal-body'>
		<textarea></textarea>
	</div>
	<div class = 'modal-footer'>
		<span class = 'btn' data-dismiss = 'modal'>Close</span>
		<span class = 'btn btn-primary save'>Save Changes</span>
	</div>
</div>

<div id = 'comment_deletion' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Delete Your Comment</h3>
	</div>
	<div class = 'modal-body'>
		Are you sure you want to delete your comment?
	</div>
	<div class = 'modal-footer'>
		<span class = 'btn' data-dismiss = 'modal'>Close</span>
		<span class = 'btn btn-danger yes-delete'>Yes, Delete</span>
	</div>
</div>

<div id = 'achievement_deletion' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Delete Achievement</h3>
	</div>
	<div class = 'modal-body'>
		Are you sure you want to delete this achievement?<br>
		Type "DELETE": <input type = 'text' />
	</div>
	<div class = 'modal-footer'>
		<span class = 'btn' data-dismiss = 'modal'>Cancel</span>
		<span class = 'btn btn-danger yes-delete'>Yes, Delete</span>
	</div>
</div>