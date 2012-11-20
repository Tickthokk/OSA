<?php foreach ($created_achievements as $ac) : ?>
<tr>
	<td>
		<?php echo $ac['game_name']; ?>
	</td>
	<td>
		<?php echo $ac['achievement_name']; ?>
	</td>
	<td>
		<?php echo $ac['added']; ?>
	</td>
	<td>
		<?php echo $ac['comments']; ?>
	</td>
	<td>
		<?php echo $ac['achievers']; ?>
	</td>
</tr>
<?php endforeach; ?>