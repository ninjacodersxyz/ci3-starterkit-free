<div class="container" id="login-page">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="new-well">

				<?php if (isset($message)): ?>
					<h3 class="text-center">Correcto!!</h3>
					<p class="text-center">Se ha enviado un mensaje de correo con las instrucciones para reestablecer su contraseña</p>
					<p class="text-center"><?= anchor(base_url(), 'Volver atras'); ?></p>
				<?php elseif (isset($error)): ?>
					<h3 class="text-center">Doh!</h3>
					<p class="text-center">Se ha producido un error, contacte a su administrador de sistema</p>
					<p class="text-center"><?= anchor(base_url(), 'Volver atras'); ?></p>
				<?php else: ?>
					<h3 class="text-center"><?= $title ?></h3>
					<p class="text-center">Ingrese su correo electronico y se le enviar las instrucciones a su correo electronico para reestablecer su contraseña</p>
					<?= form_open(current_url()); ?>
					<div class="form-group">
						<label for="email">Correo electronico</label>
						<div class="input-group">
							<span class="input-group-addon" ><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></span>
							<input type="email" class="form-control" placeholder="Correo electronico" name="email" id="email" required>
						</div>
						<?= form_error('email'); ?>
					</div>

					<p class="text-center"><?= form_submit('reiniciar', 'Reestablecer',array('class'=>'btn btn-primary btn-lg btn-block')); ?></p>
					<p class="text-center"><?= anchor(base_url(), 'Volver atras'); ?></p>

					<?= form_close(); ?>
				<?php endif ?>
				
			</div>
		</div>
	</div>
</div>