<div class="container">
	<div class="container-fluid d-flex align-items-center mb-3">
		<a class="d-flex align-items-center mb-0 mx-0 px-4" href="<?php echo base_url('produto'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
				<path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
			</svg>
		</a>
		<h2 class="d-flex align-items-center mb-0">
			Cadastro de produto
		</h2>
		
	</div>

	<?php echo form_open(!empty($produto->id) ? "produto/edit/{$produto->id}" : 'produto/add', 'class="form-control" id="form-produto"'); ?>
	<input type="hidden" id="id" name="id" value="<?php echo $produto->id; ?>" />
	<h6 class="d-flex align-items-center m-3">
		<?php $produto->id ? "Novo" : "Editar"; ?> Produto
	</h6>
	<div class="align-items-start mb-3">
		<div class="form-floating mb-3">
			<input type="name" name="name" class="form-control form-control-sm" id="name" placeholder="Nome do produto" value="<?php echo $produto->name; ?>" <?php echo $produto->active == 1 ? '' : 'readonly'; ?> />
			<label for="floatingInput">Nome do produto</label>
		</div>
		<div class="form-floating mb-3">
			<input type="text" name="price" class="form-control form-control-sm" id="price" placeholder="Valor" value="<?php echo $produto->price; ?>" <?php echo $produto->active == 1 ? '' : 'readonly'; ?> />
			<label for="floatingInput">Preço</label>
		</div>
		<div class="form-floating mb-3">
			<input type="number" name="stock" class="form-control form-control-sm" id="stock" placeholder="100" value="<?php echo $produto->stock; ?>" <?php echo $produto->active == 1 ? '' : 'readonly'; ?> />
			<label for="floatingInput">Estoque</label>
		</div>
		
		<div class="form-floating mb-3">
			<?php if ($produto->active == 1): ?>
			<button class="btn btn-primary" type="submit"><?php echo !empty($produto->id) ? "Salvar alterações" : 'Criar produto'; ?></button>
			<?php endif; ?>
		</div>
	</div>
	<?php echo form_close(); ?>

</div>
<script>
	cl.loadData()
	siterefresh.addEventListener('click', function(event) {
		event.preventDefault()
		cl.loadData()
	}, true)
</script>
