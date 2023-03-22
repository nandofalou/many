<div class="container">
	<div class="container-fluid d-flex align-items-center mb-3">
		<a class="d-flex align-items-center mb-0 mx-0 px-4" href="<?php echo base_url('dashboard'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
				<path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
			</svg>
		</a>
		<h2 class="d-flex align-items-end mb-0">
			Loja
		</h2>
	</div>
	<div class="row m-2">
		<div class="col-lg-8">
			<div class="row row-cols-1 row-cols-md-3 mb-3 text-center" id="productList">
				<?php foreach ($produtos as $produto) : ?>
					<div class="col-4">
						<div class="card mb-4 rounded-3 shadow-sm">
							<div class="card-header py-3">
								<h4 class="my-0 fw-normal"><?php echo $produto->name; ?></h4>
							</div>
							<div class="card-body">
								<h1 class="card-title pricing-card-title">R$ <?php echo number_format($produto->price, 2, ',', '.'); ?></h1>
								<div class="d-flex justify-content-center">
									<div class=""><button type="button" class="btn btn-sm btn-outline-primary btn-add" data-id="<?php echo $produto->id; ?>" data-price="<?php echo $produto->price; ?>" data-name="<?php echo $produto->name; ?>"><i class="bi bi-plus-circle-fill"></i></button></div>
									<div class=""><button type="button" class="btn btn-sm btn-outline-danger btn-del" data-id="<?php echo $produto->id; ?>" data-price="<?php echo $produto->price; ?>" data-name="<?php echo $produto->name; ?>"><i class="bi bi-dash-circle-fill"></i></button></div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="form-control">
				<p class="mb-2">Carrinho de compras</p>
				<select class="form-select mb-3" aria-label="Default select example" id="fornecedorId" name="fornecedorId">
					<option value="" selected>Selecione um fornecedor</option>
					<?php foreach ($fornecedores as $fornecedor) : ?>
						<option value="<?php echo $fornecedor->id; ?>"><?php echo $fornecedor->name; ?></option>
					<?php endforeach; ?>
				</select>
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="obs" placeholder="Observação">
					<label for="obs">Escreva a observação</label>
				</div>
				<div>
					<div class="row my-2">
						<div class="col">Item</div>
						<div class="col">Qtd</div>
						<div class="col">Valor Total</div>
					</div>
					<div id="lista" class="my-2">

					</div>
					<button type="button" class="btn btn-sm btn-outline-primary" id="btn-go"><i class="bi bi-cart-check-fill"></i> Finalizar venda</button>
				</div>
			</div>
		</div>
	</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script>
	const cart = {
		item: [],
		fornecedorId: null,
		obs: null
	}

	$(document).ready(function() {
		$("#productList").on('click', 'button.btn-add', function() {
			let productId = $(this).data('id')
			let price = $(this).data('price')
			let name = $(this).data('name')
			addProduct(productId, price, name)
		});

		$("#productList").on('click', 'button.btn-del', function() {
			let productId = $(this).data('id')
			removeProduct(productId)
		});
		$("#btn-go").on('click', function() {
			goCart()
		});
	})

	function addProduct(productId, price, name) {
		const index = cart.item.findIndex(el => el.productId === productId)
		if (index !== -1) {
			cart.item[index].quantity++
			montaCarrinho()
		} else {
			cart.item.push({
				productId,
				price,
				name,
				quantity: 1
			})
			montaCarrinho()
		}
	}

	function removeProduct(productId) {
		const index = cart.item.findIndex(el => el.productId === productId)
		if (index !== -1) {
			if (cart.item[index].quantity > 0) {
				cart.item[index].quantity--
				if (cart.item[index].quantity === 0) {
					cart.item.splice(index, 1)
				}
				montaCarrinho()
			} else {
				cart.item.splice(index, 1)
				montaCarrinho()
			}
		}
	}

	function montaCarrinho() {
		$("#lista").empty();
		cart.item.forEach(el => {
			let vl = el.price * el.quantity
			$("#lista").append(`
				<div class="row">
					<div class="col">${el.name}</div>
					<div class="col">${el.quantity}</div>
					<div class="col">R$ ${vl}</div>
				</div>
			`)
		})
	}

	function goCart() {
		var fornecedorId = $('select[name="fornecedorId"]').val();
		var obs = $('#obs').val();
		var error = [];
		if (!fornecedorId) {
			error.push('Por favor, selecione um fornecedor')
			$('select[name="fornecedorId"]').focus()
		}
		if (!obs) {
			error.push('Por favor, informe a observação')
			$('#obs').focus();
		}

		if (cart.item.length < 1) {
			error.push('Por favor, selecione ao menos um produto')
		}

		if (error.length === 0) {
			goSale()
		} else {
			error.forEach(el => {
				alert(el)
			})
		}
	}

	function goSale() {
		cart.fornecedorId = $('select[name="fornecedorId"]').val()
		cart.obs = $('#obs').val()

		$.post('<?php echo base_url('loja/order'); ?>', cart, function(resp) {
				if (resp && resp.status) {
					window.location = "<?php echo base_url('loja/order/'); ?>" + resp.data.order;
				}
			}).done(function() {
				//
			})
			.fail(function(error) {
				alert(error.responseJSON.message)
			})
	}
</script>
