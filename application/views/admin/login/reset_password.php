<div class="container" id="login-page">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="new-well">
				<?php if (!isset($invalid)): ?>
					<h3 class="text-center"><?= $title ?></h3>
					<?=$errors?>
					<?=$form?>
					<div class="clearfix"></div>
				<?php elseif (isset($reset)): ?>
					<h3>Solicitud exitosa</h3>
					<p class="text-center">La contraseña ha sido actualizada correctamente</p>
					<p class="text-center"><?= anchor(base_url(), 'Volver atras'); ?></p>
				<?php else: ?>
					<h3>Solicitud invalida</h3>
					<p class="text-center">La solicitud para reestablecer su contraseña es invalida.</p>
					<p class="text-center"><?= anchor(base_url(), 'Volver atras'); ?></p>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>