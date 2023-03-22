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
	<div class="form-control">
		<input type="hidden" id="id" name="id" value="<?php echo $colaborador->id; ?>" />
		<h6 class="mt-3">
			<?php $colaborador->id ? "Novo" : "Editar"; ?> Colaborador
		</h6>
		<div class="align-items-start mb-3">
			<div class="form-floating mb-3">
				<div class="form-control form-control-sm"><?php echo sprintf("%'.04d\n", $colaborador->id); ?> - <?php echo $colaborador->name; ?></div>
				<label for="floatingInput">Colaborador</label>
			</div>
			<div class="mb-3">
				<h6>Endereços</h6>
				<div class="form-control">
					<p>Adicionar novo endereço</p>
					<div class="row">
						<div class="col">
							<label for="cep" class="form-label">CEP</label>
							<input type="text" class="form-control" id="cep" placeholder="12345678">
						</div>
						<div class="col">
							<label for="number" class="form-label">Número</label>
							<input type="text" class="form-control" id="number" placeholder="45a">
						</div>
						<div class="col">
							<span class="form-label">Complemento</span>
							<div class="input-group pt-2">
								<input type="text" id="complement" class="form-control" placeholder="Condomínio azul">
								<button id="bt-add" class="btn btn-sm btn-primary">Adicionar endereço</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-control" id="addreslist">
				&nbsp;
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$("#bt-add").click(function() {
			let data = {
				id: $("#id").val(),
				zipcode: $("#cep").val(),
				number: $("#number").val(),
				complement: $("#complement").val(),
			}
			addAddress(data)
		})

		$("#addreslist").on('click', '.bt-del', function(){
			remove($(this).data('id-address'))
		})
		getaddress()
	})

	function addAddress(data) {

		$.post('<?php echo base_url('colaborador/address'); ?>', data, function(resp) {
				console.log(resp);
				if (resp) {
					if (resp.status) {
						getaddress()
					} else {
						alert(resp.message)
					}
				}
			}).done(function() {
				//
			})
			.fail(function(error) {
				alert(error.responseJSON.message)
			})
	}

	function getaddress() {
		let idUser = $("#id").val()
		$("#addreslist").empty()
		let baseUrl = '<?php echo base_url('colaborador'); ?>'
		$.get(baseUrl+'/alladdress/' + idUser, function(resp) {
			if (resp && resp.status) {
				resp.data.forEach(el => {
					$('#addreslist').append(`
						<address class='shadow-lg p-3 mb-2 bg-body-tertiary rounded'>
							Endereço: ${el.street} n° ${el.number} - ${el.district}<br/>
							${el.city}/${el.state} cep: ${el.zipcode}<br />
							${el.complement}<br />
							<button class="btn btn-sm btn-danger bt-del" data-id-address="${el.id}">Excluir</button>
						</address>
					`)
				})
			}
		})
	}
	function remove(idAddr) {
		let idUser = $("#id").val()
		let baseUrl = '<?php echo base_url('colaborador'); ?>'
		$.get(baseUrl+'/removeaddress/' + idUser+'/'+idAddr, function(data) {
			getaddress()
		})
	}
</script>
