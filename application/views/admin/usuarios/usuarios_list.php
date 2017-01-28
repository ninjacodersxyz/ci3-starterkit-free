<ul class="list-inline clearfix">
	<li><h2 class="page-title">Listado de usuarios</h2></li>
	<li class="pull-right"><?php echo anchor('admin/usuarios/add', 'Agregar usuario','class="btn btn-primary"'); ?></li>
</ul>
<?php if ($new): ?>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		Usuario agregado correctamente
	</div>
<?php endif ?>
<?php if ($edit): ?>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		Usuario editado correctamente
	</div>
<?php endif ?>
<div class="new-well">
	<div class="table-responsive"><?= $tabla ?></div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#listado').dataTable({
			columnDefs: [{
				targets: 6,
				serchable:false,
				sortable: false,
				class: 'text-center',
				render: function ( val ) {
					return '<a href="<?= base_url() ?>admin/usuarios/edit/'+val+'" title="Editar" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>&nbsp;<a title="Eliminar" data-id="'+val+'" class="eliminar btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
				}
			}]
		});
		$('.eliminar').on('click', function(event) {
			event.preventDefault();
			var btn = $(this);
			bootbox.confirm("Desea eliminar la asignatura?", function(res) {
				if (res){
					$.post('<?php echo site_url("admin/usuarios/ajax_delete_usuario") ?>', { id: btn.data('id') }, function(data) {
						bootbox.alert(data, function () {
							document.location.reload(true);
						});
					}).fail(function (data) {
						bootbox.alert(data.responseText);
					});
				}
			});
		});
	});
</script> 