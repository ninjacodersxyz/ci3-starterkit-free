<div class="usuarios-roles">
	<ul class="list-inline clearfix">
		<li><h2 class="page-title">Administrar Usuarios del Rol</h2></li>
		<li class="pull-right"><?php echo anchor('admin/roles', '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Volver los roles','class="btn btn-primary"'); ?></li>
	</ul>	
	<div class="new-well">
		<h4 class="page-subtitle"><strong>Rol:</strong> <?= $rol->rol ?> <?php if (strlen($rol->descripcion)): ?>- <small><?= $rol->descripcion ?></small><?php endif ?></h4>
		<div class="row">
			<div class="col-xs-5">
				<select name="from[]" id="user_list" class="form-control" size="13" multiple="multiple">
					<?php foreach ($inhabilitados as $key => $value): ?>
						<option value="<?= $key ?>"><?= $value ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="col-xs-2">
				<button type="button" id="user_list_undo" class="btn btn-primary btn-block">Deshacer</button>
				<button type="button" id="user_list_rightAll" class="btn btn-default btn-block"><i class="glyphicon glyphicon-forward"></i></button>
				<button type="button" id="user_list_rightSelected" class="btn btn-default btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
				<button type="button" id="user_list_leftSelected" class="btn btn-default btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
				<button type="button" id="user_list_leftAll" class="btn btn-default btn-block"><i class="glyphicon glyphicon-backward"></i></button>
				<button type="button" id="user_list_redo" class="btn btn-info btn-block">Rehacer</button>
				<button type="button" id="guardar_cambios" class="btn btn-primary btn-block">Guardar cambios</button>
			</div>
			<div class="col-xs-5">
				<select name="to[]" id="user_list_to" class="form-control usuarios" size="13" multiple="multiple">
					<?php foreach ($habilitados as $key => $value): ?>
						<option value="<?= $key ?>"><?= $value ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function getValues(selector) {
		return $.map($(selector).children('option'), function(item, index) {
			return $(item).val();
		});
	};
	$(document).ready(function() {

		$('#user_list').multiselect({
			keepRenderingSort: true,
			sort:false
		});

		$('#guardar_cambios').click(function(event) {
			event.preventDefault();
			var usuarios = getValues('.usuarios');
			$.post('<?= base_url() ?>admin/roles/ajax_manage_users_roles', {id: '<?= $this->uri->segment(4, 1) ?>',usuarios: usuarios.join(',') }, function(data) {
				showToast({
					heading: 'Operacion correcta!',
					text: "Se actualizaron los datos en la base de datos",
					icon: 'success',
					stack: 5
				});
			});
		});

	});
</script>

