<?php foreach ($achievement_comments as $ac) : ?>
<tr>
	<td>
		<?php echo $ac['game_name']; ?>
	</td>
	<td>
		<?php echo $ac['achievement_name']; ?>
	</td>
	<td>
		<?php echo Markdown($ac['comment']); ?>
	</td>
	<td>
		<?php echo $ac['added']; ?>
	</td>
</tr>
<?php endforeach; ?>