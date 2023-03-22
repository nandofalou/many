<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
<?php 
	$flashError = $this->session->flashdata('error');
	$flashSuccess = $this->session->flashdata('success');

	if (!empty($flashError)):
		foreach ($flashError as $field => $error) :
			?>
			<div class="alert alert-danger my-3"><?php echo $error; ?></div>
			<?php
		endforeach;
	endif;
	
	if (!empty($flashSuccess)):
		foreach ($flashSuccess as $field => $suss) :
			?>
			<div class="alert alert-success my-3"><?php echo $suss; ?></div>
			<?php
		endforeach;
	endif;
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
