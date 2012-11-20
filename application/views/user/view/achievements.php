<?php foreach ($achievements as $a) : ?>
<tr>
	<td>
		<?php echo $a['game_name']; ?>
	</td>
	<td>
		<?php echo $a['achievement_name']; ?>
	</td>
	<td>
		<?php echo $a['achieved']; ?>
	</td>
</tr>
<?php endforeach; ?>