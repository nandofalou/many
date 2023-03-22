<div class="container">
	<div class="container-fluid d-flex align-items-center mb-3">
		<a class="d-flex align-items-center mb-0 mx-0 px-4" href="<?php echo base_url('colaborador'); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
				<path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
			</svg>
		</a>
		<h2 class="d-flex align-items-center mb-0">
			Cadastro de colaborador
		</h2>
		
	</div>

	<?php echo form_open(!empty($colaborador->id) ? "colaborador/edit/{$colaborador->id}" : 'colaborador/add', 'class="form-control" id="form-colaborador"'); ?>
	<input type="hidden" id="id" name="id" value="<?php echo $colaborador->id; ?>" />
	<h6 class="d-flex align-items-center m-3">
		<?php $colaborador->id ? "Novo" : "Editar"; ?> Colaborador
	</h6>
	<div class="align-items-start mb-3">
		<div class="form-floating mb-3">
			<input type="name" name="name" class="form-control form-control-sm" id="floatingInput" placeholder="Nome do colaborador" value="<?php echo $colaborador->name; ?>" <?php echo $colaborador->active == 1 ? '' : 'readonly'; ?> />
			<label for="floatingInput">Nome do colaborador</label>
		</div>
		<div class="form-floating mb-3">
			<input type="email" name="email" class="form-control form-control-sm" id="floatingInput" placeholder="name@example.com" value="<?php echo $colaborador->email; ?>" <?php echo $colaborador->active == 1 ? '' : 'readonly'; ?> />
			<label for="floatingInput">Email</label>
		</div>
		<div class="mb-3">
			<h6>Permissões</h6>
			<?php foreach ($permissions as $k => $permission) : ?>
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" role="switch" name="permission[]" id="sw-<?php echo $k; ?>" value="<?php echo $permission->id; ?>" <?php echo $permission->activated == 1 ? "checked" : ""; ?>>
					<label class="form-check-label" for="sw-<?php echo $k; ?>"><?php echo $permission->description; ?></label>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="form-floating mb-3">
			<?php if ($colaborador->active == 1): ?>
			<button class="btn btn-primary" type="submit"><?php echo !empty($colaborador->id) ? "Salvar alterações" : 'Criar colaborador'; ?></button>
			<?php endif; ?>
		</div>
	</div>
	<?php echo form_close(); ?>

</div>
