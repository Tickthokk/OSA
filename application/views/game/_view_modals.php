<div id = 'flag_for_bad_link' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Flagging Link</h3>
	</div>
	<div class = 'modal-body'>
		<p>You are flagging a link from "<?php echo $this->game->name; ?>" as inappropriate.  A moderator will review, and if warranted, the link will be removed.</p>

		<form class = 'form-horizontal'>
			<fieldset>
				<div class = 'control-group'>
					<label class = 'control-label'>Which link?</label>
					<div class = 'controls'>
						<select></select>
					</div>
				</div>
			</fieldset>
		</form>

		<p><strong>Reason:</strong></p>
		<textarea></textarea>
		<p>Thanks for keeping OSA Tidy!</p>
	</div>
	<div class = 'modal-footer'>
		<span class = 'btn' data-dismiss = 'modal'>Cancel</span>
		<span class = 'btn btn-danger flag-link-go'>Flag Link</span>
	</div>
</div>

<div id = 'flag_as_inappropriate' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Flagging Game</h3>
	</div>
	<div class = 'modal-body'>
		<p>You are flagging "<?php echo $this->game->name; ?>" as inappropriate.  A moderator will review, and if warranted, the appropriate actions will be taken.</p>
		<p><strong>Reason:</strong></p>
		<textarea></textarea>
		<p>Thanks for keeping OSA Tidy!</p>
	</div>
	<div class = 'modal-footer'>
		<span class = 'btn' data-dismiss = 'modal'>Cancel</span>
		<span class = 'btn btn-danger flag-go'>Flag Game</span>
	</div>
</div>

<div id = 'game_link_go' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Now Leaving Site</h3>
	</div>
	<div class = 'modal-body'>
		Warning, a moderator has not approved this link.  You are leaving our site at your own risk.  Your destination is:
		<span class = 'goto'>http://link</span>
	</div>
	<div class = 'modal-footer'>
		<span class = 'btn' data-dismiss = 'modal'>Stay</span>
		<span class = 'btn btn-danger link-go'>Take me there</span>
	</div>
</div>

<div id = 'game_suggest_link' class = 'modal hide fade'>
	<div class = 'modal-header'>
		<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;</button>
		<h3>Suggest a Link</h3>
	</div>
	<div class = 'modal-body'>
		<p>
			Links can be Informational sites, Fan sites, Youtube channels, Speed runs or anything centered around <?php echo $this->game->name; ?>!
		</p>
		<form class = 'form-horizontal'>
			<fieldset>
				<div class = 'control-group'>
					<label class = 'control-label' for = 'site'>Link Name</label>
					<div class = 'controls'>
						<input type = 'text' name = 'site' class = 'input-xlarge'>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>URL</label>
					<div class = 'controls'>
						<div class = 'label-over'>
							<label class = 'over-apply' for = 'suggestedLink'>http://site.com/whatever</label>
							<input type = 'text' name = 'url' id = 'suggestedLink' class = 'input-xlarge' title = ''>
						</div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class = 'modal-footer'>
		<span class = 'btn' data-dismiss = 'modal'>Cancel</span>
		<span class = 'btn btn-danger submit-link'>Submit Link</span>
	</div>
</div>