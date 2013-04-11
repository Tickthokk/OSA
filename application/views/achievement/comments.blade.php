<div class='well' id='comments' data-top-comment-id='{{ $top_comment_id }}' data-current-comment-count='5'>
	<h2>Comments ({{ $achievement->comment_tally }})</h2>
	@unless($achievement->comment_tally)
	<div class='well no-comments'>
		<em>None!</em>  Be the first!
	</div>
	@else
	@foreach ($achievement->comments as $comment)
	<div class='well user_comment' data-id='{{ $comment->id }}' data-comment='{{ htmlentities($comment->comment, ENT_QUOTES) }}'>
		<div class='buttons'>
			@if(Auth::check() AND $comment->user_id == Auth::user()->id) 
			@if($comment->admin_lock)
			<i class='icon-ban-circle' title='A moderator has edited your comment. You no longer own this comment.'></i>
			@else
			<span class='edit_comment' data-toggle='modal' data-target='comment_editing' title='Edit Comment'>
				<i class='icon-pencil'></i>
			</span>
			<span class='delete_comment' title='Delete Comment'>
				<i class='icon-trash'></i>
			</span>
			@endif
			@else
			<span class='flag_comment opaque hover_show' title='Flag Comment as Inappropriate'>
				<i class='icon-flag'></i>
			</span>
			@endif
		</div>
		<p>
			<a href='/user/profile/{{ $comment->user_id }}#{{ $comment->user->username }}' title='View Profile'>{{ $comment->user->username }}</a>
			<span class='created-modified'>
				{{ DateFmt::Format('AGO[t]IF-FAR[d##my]', strtotime($comment->created_at)) }}
			</span>
		</p>
		<blockquote class='markdown-container@if($comment->admin_lock) admin_lock@endif'>
			{{ $comment->markdown_comment }}
		</blockquote>
	</div>
	@endforeach
	@endif
</div>

{{ Form::open('/comment/' . $achievement->id, 'POST', array('class' => 'well form-horizontal'))}}
	<fieldset>
		<legend>Leave a comment</legend>

		<div class='control-group'>
			<label class='control-label' for='description'>
				Comment
			</label>
			<div class='controls'>
				<textarea name='comment' class='input-xlarge' rows='6'>{{ $old_comment }}</textarea>
				<p class="help-block markdown-container">
					<em>We use a restricted version of 
					<span class='btn btn-small' id='markdown_popup' data-trigger='click' data-placement='left' data-original-title='Restricted Markdown Example'>
						<i class='icon-align-right'></i>
						Markdown
					</span>.</em>
				</p>
				@include('markdown.example')
			</div>
		</div>

		<div class='controls'>
			<button class='btn btn-primary'>
				Submit
			</button>
		</div>
	</fieldset>
</form>