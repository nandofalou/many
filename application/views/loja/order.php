<div class="container">
	<div class="container-fluid d-flex align-items-center mb-3">
		<a class="d-flex align-items-center mb-0 mx-0 px-4" href="<?php echo base_url('dashboard'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
				<path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
			</svg>
		</a>
		<h2 class="d-flex align-items-end mb-0">
			Pedido
		</h2>
	</div>
	<div class="form-control p-2">
		<h3>Pedido N° <?php echo $order->id; ?></h3>
		<p>
			Fornecedor: <strong><?php echo $order->fornecedor; ?></strong></br />
			Data: <strong><?php echo date('d/m/Y H:i:s', strtotime($order->created_at)); ?></strong></br />
			Colaborador: <strong><?php echo date('d/m/Y H:i:s', strtotime($order->username)); ?></strong></br />
			<hr />
		</p>
		<div class="alert alert-secondary" role="alert">Observações:<br />
			<span><?php echo $order->obs; ?></span>
		</div>
		<hr />
		<table class="table">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Item</th>
					<th scope="col">Quantidade</th>
					<th scope="col">Valor Unitário</th>
					<th scope="col">Valor Valor Total</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$resume = (object)[
					'valorTotal' => 0,
					'qtdItens' => 0
				];

				foreach ($orderItems as $i => $item) :
					$total = $item->quantity * $item->price;
					$resume->valorTotal += $total;
					$resume->qtdItens += $item->quantity;
				?>
					<tr>
						<td><?php echo $i + 1; ?></td>
						<td><?php echo $item->product_name; ?></td>
						<td><?php echo $item->quantity; ?></td>
						<td>R$ <?php echo number_format($item->price, 2, ',', '.'); ?></td>
						<td>R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
					</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="alert alert-warning" role="alert">
		<span>Status da venda: <?php echo $order->status; ?></span><br />
		<span>Valor total do pedido: R$  <?php echo number_format($resume->valorTotal, 2, ',', '.'); ?></span><br />
	</div>
</div>
