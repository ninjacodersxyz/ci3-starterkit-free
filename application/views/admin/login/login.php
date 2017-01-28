<div class="container" id="login-page">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="new-well">

				<?php if (!isset($blocked)): ?>

					<h3 class="text-center"><?= $title ?></h3>
					<?php if (isset($mensaje)): ?>
						<div class="alert alert-info" role="alert"><?= $mensaje ?></div>
					<?php endif ?>

					<?= form_open(current_url()); ?>

					<div class="form-group">
						<label for="usuario">Usuario</label>
						<div class="input-group">
							<span class="input-group-addon" ><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
							<input type="text" class="form-control" placeholder="Usuario" name="usuario" id="usuario">
						</div>
						<?php if (form_error('usuario')): ?>
							<span class="help-block">El <strong>usuario</strong> es requerido</span>
						<?php endif ?>
					</div>

					<div class="form-group">
						<label for="password">Password</label>
						<div class="input-group">
							<span class="input-group-addon" ><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
							<input type="password" class="form-control" placeholder="Password" name="password" id="password">
						</div>
						<?php if (form_error('password')): ?>
							<span class="help-block">El <strong>password</strong> es obligatorio</span>
						<?php endif ?>
					</div>

					<hr>

					<p class="text-center"><?= form_submit('enviar', 'Ingresar',array('class'=>'btn btn-primary btn-lg btn-block')); ?></p>

					<?= form_close(); ?>

					<p class="text-center"><?= anchor('login/forget_password', 'Olvidaste tu contraseña?'); ?></p>

				<?php else: ?>

					<h3 class="text-center">Se ha bloqueado el acceso al sistema desde su computador debido a que ha sobrepasado la cantidad de intentos fallidos posibles.</h3>
					<small>* Podra reintentarlo en: <strong><span class="reintentar"><?= $blocked ?> minuto(s)</span></strong></small>
					<script type="text/javascript">
						$(document).ready(function() {
							var left = <?= $blocked ?>,i=0;
							setInterval(function () {
								i++;
								if ((left-i) ==0) location.reload();
								else $('.reintentar').text((left-i)+ ' minuto(s)');
							}, 60000);
						});
					</script>
				<?php endif; ?>

				<a href="https://www.boliviasoftware.com/" target="_blank"><img src="assets/imgs/boliviasoftware.png" alt="Bolivia Software logo" class="boliviasoftware-logo img-responsive"></a>
			</div>
		</div>
	</div>
</div>