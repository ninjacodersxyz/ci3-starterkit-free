<h2 class="page-title"><?= $titulo_formulario ?></h2>
<?php if (isset($edited)): ?>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		Perfil correctamente editado
	</div>
<?php endif ?>
<div class="new-well">
	<?=$errors?>
	<?=$form?>
	<div class="clearfix"></div>
</div>