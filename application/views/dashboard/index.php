<div class="container">
	<h3 class="my-3">Lista de Pedidos</h3>
	<table id="myTable" class="table table-bordered table-striped table-hover">
		<thead class="">
			<tr>
				<th>Pedido n°</th>
				<th>Data</th>
				<th>Status</th>
				<th>Fornecedor</th>
				<th>Colaborador</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($orders as $order) : ?>
				<tr>
					<td><?php echo $order->id; ?></td>
					<td><?php echo date('d/m/Y',strtotime($order->created_at)); ?></td>
					<td><?php echo $order->status; ?></td>
					<td><?php echo $order->fornecedor; ?></td>
					<td><?php echo $order->username; ?></td>
					<td>
						<a href="<?php echo base_url('loja/order/'.$order->id); ?>" class="btn btn-sm btn-secondary"><i class="bi bi-printer-fill"></i></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
