					</div>
				</div>
			</div>
		</div>
		<footer>
			&copy; Nick Wright <?php echo date('Y'); ?>
		</footer>
		<section id = 'alerts'>
			<div class = 'row'>
				<div class = 'span4 message_box'>
					<div id = 'message_template' class = 'hidden alert alert-block fade in'>
						<button type = 'button' class = 'close' data-dismiss = 'alert'>&times;</button>
						<h4 class = 'alert-heading'></h4>
						<p></p>
					</div>
				</div>
			</div>
		</section>
		<?php if ($firewall_enabled) : ?>
		<script src = '/assets/firewall/jquery.min.js' type = 'text/javascript'></script>
		<script src = '/assets/firewall/jquery-ui.min.js' type = 'text/javascript'></script>
		<?php else : ?>
		<script src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type = 'text/javascript'></script>
		<script src = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js' type = 'text/javascript'></script>
		<?php endif; ?>
		<script src = '/assets/js/bootstrap.min.js' type = 'text/javascript'></script>
		<!-- <script src = '/assets/js/jquery/highcharts.js' type = 'text/javascript'></script>
		<script src = '/assets/js/jquery/inspiritas.min.js' type = 'text/javascript'></script> -->
		<script src = '/assets/js/jquery/jquery.dataTables.min.js' type = 'text/javascript'></script>
		<script src = '/assets/js/jquery/datatables_bootstrap.js' type = 'text/javascript'></script>
		<script src = '/assets/js/_osa.js' type = 'text/javascript'></script>
		<?php foreach ($js as $j) : ?>
		<script src = '/assets/js/<?php echo $j; ?>.js' type = 'text/javascript'></script>
		<?php endforeach; ?>
	</body>
</html>