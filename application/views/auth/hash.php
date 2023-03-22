<div class="container">
    <div class="card">
        <div class="card-header">
            Recuperação de senha
        </div>
        <div class="card-body">
            <h5 class="card-title">Este procedimento não foi implementado.</h5>
            <p class="card-text">O próximo passo seria enviar um email para o usuário com o link de recuperar a senha.</p>
            <p class="card-text">Click no link abaixo para alterar a senha.</p>
            <a href="<?php echo base_url('auth/resetpassword').'?hash=' . $hash->hash; ?>" class=""><?php echo base_url('auth/resetpassword').'?hash=' . $hash->hash; ?></a>
        </div>
    </div>
</div>
