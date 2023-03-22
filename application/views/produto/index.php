<div class="container">
	<div class="container-fluid d-flex align-items-center mb-3">
		<a class="d-flex align-items-center mb-0 mx-0 px-4" href="<?php echo base_url('dashboard'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
				<path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
			</svg>
		</a>
		<h2 class="d-flex align-items-end mb-0">
			Cadastro de produtos
		</h2>
		<a class="ms-auto btn bt-sm btn-primary" href="<?php echo base_url('produto/add'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
				<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
			</svg> Novo produto
		</a>
	</div>
	<table id="myTable" class="table table-bordered table-striped table-hover">
		<thead class="">
			<tr>
				<th>ID</th>
				<th>NOME</th>
				<th>PREÇO</th>
				<th>ESTOQUE</th>
				<th>STATUS</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($produtos as $line) : ?>
				<tr>
					<td><?php echo $line->id; ?></td>
					<td><?php echo $line->name; ?></td>
					<td><?php echo number_format($line->price,2,',','.'); ?></td>
					<td><?php echo $line->stock; ?></td>
					<td>
						<bunton class="bt-active btn btn-sm" data-idproduto="<?php echo $line->id; ?>" data-sts="<?php echo $line->active; ?>">
							<?php if ($line->active == 1) : ?>
								<i class="bi bi-check-circle-fill" style="color: cornflowerblue;"></i>
							<?php else : ?>
								<i class="bi bi-x-circle-fill red" style="color: red;"></i>
							<?php endif; ?>
						</bunton>
					</td>
					<td>
						<a class="btn btn-sm btn-primary" href="<?php echo base_url('produto/edit/' . $line->id); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
								<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
								<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
							</svg>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$(".bt-active").click(function() {
			let data = {
				id: $(this).data('idproduto'),
				active: $(this).data('sts') == 1 ? 0 : 1
			}
			changeStatus(data)
		})
	})

	function changeStatus(data) {

		$.post('<?php echo base_url('produto/changests'); ?>', data, function(resp) {
			if(resp) {
				$(`.bt-active[data-idproduto=${resp.data.id}]`).data('sts', resp.data.active)
				if(resp.data.active == 1) {
					$(`.bt-active[data-idproduto=${resp.data.id}]`).html('<i class="bi bi-check-circle-fill" style="color: cornflowerblue;"></i>')
				} else {
					$(`.bt-active[data-idproduto=${resp.data.id}]`).html('<i class="bi bi-x-circle-fill" style="color: red;"></i>')
				}
			}
		})
		
	}
</script>
