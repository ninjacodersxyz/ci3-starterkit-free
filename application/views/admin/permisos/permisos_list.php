<div id="permisos">
	<ul class="list-inline clearfix">
		<li><h2 class="page-title">Permisos en el sistema</h2></li>
		<li class="pull-right"><?php echo anchor('admin/permisos/add', 'Agregar interfaz','class="btn btn-primary"'); ?></li>
	</ul>
	<?php if ($new): ?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Interfaz agregada correctamente
		</div>
	<?php endif ?>
	<?php if ($edit): ?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Interfaz editada correctamente
		</div>
	<?php endif ?>
	<div class="new-well">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-condensed new-table">
				<thead>
					<tr>
						<th rowspan="2">Controller / Function</th>
						<th class="text-center" colspan="<?= count($roles) ?>">Roles</th>
						<th rowspan="2">Operaciones</th>
					</tr>
					<tr>
						<?php foreach ($roles as $k1 => $v1): ?>
							<th class="text-center" title="Rol: <?= $v1['rol'] ?>"><?= $v1['rol'] ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($permisos as $k1 => $v1): ?>
						<tr class="text-center">
							<td class="text-left"><?= $permisos[$k1]['clase'].'  / '.$permisos[$k1]['funcion'] ?>&nbsp;<a data-id="<?= $permisos[$k1]['id'] ?>" data-clase="<?= $permisos[$k1]['clase'] ?>" data-funcion="<?= $permisos[$k1]['funcion'] ?>" class="btn btn-link btn-xs ayuda" title="Como usar el permiso en el sistema"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td>
							<?php foreach ($roles as $k2 => $v2): ?>
								<td><input type="checkbox" data-permiso="<?= $permisos[$k1]['id'] ?>" data-rol="<?= $v2['id'] ?>" class="change-status" <?= (in_array($v2['id'],$permisos[$k1]['roles'])) ? 'checked' : '' ?>></td>
							<?php endforeach ?>
							<td class="operaciones"><?= anchor('admin/permisos/edit/'.$v1['id'], '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', 'class="btn btn-primary btn-xs"'); ?>&nbsp;
								<a title="Eliminar" data-id="<?= $v1['id'] ?>" class="eliminar btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.ayuda').click(function(event) {
				event.preventDefault();
				bootbox.dialog({
					message: 
					'<h5>Para usar la validacion introduzca el codigo <strong>"$this->functions->check_permissions("'+$(this).data('id')+'");"</strong> en la funcion <strong>"'+$(this).data('funcion')+'"</strong> de la clase <strong>"'+$(this).data('clase')+'"</strong> como en el siguiente ejemplo:</h5>'+
					'<div class="well">'+
					'<code class="codigo-ejemplo">'+
					'<span class="text-info">class</span> <span class="text-success">'+$(this).data('clase')+'</span> <span class="text-danger">extends</span> <span class="text-success">CI_Controller</span> {<br>..........'+
					'<div class="indent"><span class="text-danger">public</span> <span class="text-primary">function</span> <span class="text-sucess">'+$(this).data('funcion')+'</span>(){<br><div class="indent"><span class="text-warning">$this</span><span class="text-danger">-></span>functions<span class="text-danger">-></span><span class="text-primary">check_permissions</span>(<span class="text-info">"'+$(this).data('id')+'"</span>);<br>..........</div><br>}</div>'+
					'..........<br>}'+
					'</code>'+
					'</div>', 
					size: 'large',
					backdrop: '1',
					title: 'Como usar la validacion?'
				});
			});
			$('.change-status').change(function() {
				$.post('<?= base_url() ?>admin/permisos/ajax_modify_permiso', {rol: $(this).data('rol'), permiso: $(this).data('permiso'),status: (this.checked) ? 1 : 0}, function(data, textStatus, xhr) {
					showToast({
						heading: 'Operacion correcta!',
						text: "Se modifico correctamente el registro",
						stack: 2
					});
				});
			});
			$('.eliminar').click(function(event) {
				var self = $(this);
				event.preventDefault();
				bootbox.confirm("Desea eliminar el permiso?", function(res) {
					if (res) {
						$.post('<?= base_url() ?>admin/permisos/ajax_delete_permiso', { id: self.data('id')}, function(data, textStatus, xhr) {
							if (data) {
								showToast({
									heading: 'Operacion correcta!',
									text: "Se modifico correctamente el registro",
									icon: 'success',
									stack: 2
								});
								self.parents('tr').remove();
							}else{
								showToast({
									heading: 'Ocurrio un error!',
									text: "Intentelo nuevamente mas tarde",
									icon: 'warning',
									stack: 2
								});
							}
						});
					}
				});
			});
		});
	</script>

	
