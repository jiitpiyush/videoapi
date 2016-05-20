<?php
	foreach ($_POST as $key => $value) {
		?>
		<script type="text/javascript"> alert(<?php echo $value;?>)</script>
	<?php
	}
	?>