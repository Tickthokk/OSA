		</div>
		<footer>
			&copy; Nick Wright <?php echo date('Y'); ?>
		</footer>
		<script src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type = 'text/javascript'></script>
		<script src = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js' type = 'text/javascript'></script>
		<script src = '/assets/scripts/bootstrap.min.js' type = 'text/javascript'></script>
		<script src = '/assets/scripts/_osa.js' type = 'text/javascript'></script>
		<?php if (isset($js)) : ?>
		<?php foreach ($js as $j) : ?>
		<script src = '/assets/scripts/<?php echo $j; ?>.js' type = 'text/javascript'></script>
		<?php endforeach; ?>
		<?php endif; ?>
	</body>
</html>