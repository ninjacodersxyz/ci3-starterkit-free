<ul class="list-inline clearfix">
	<li><h2 class="page-title">Listado de roles</h2></li>
	<li class="pull-right"><?php echo anchor('admin/roles/add', 'Agregar Rol','class="btn btn-primary"'); ?></li>
</ul>
<?php if ($new): ?>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		Rol agregado correctamente
	</div>
<?php endif ?>
<?php if ($edit): ?>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		Rol editado correctamente
	</div>
<?php endif ?>
<div class="new-well">
	<div class="table-responsive"><?= $tabla ?></div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#listado').dataTable({
			columnDefs: [{
				targets: 2,
				serchable: false,
				render: function (val) {
					return (val ==1) ? 'Habilitado': 'Inhabilitado';
				}
			},{
				targets: 3,
				serchable: false,
				width:'10%',
				class: 'text-center',
				sortable: false,
				render: function ( val ) {
					return '<a href="<?= base_url() ?>admin/roles/edit/'+val+'" title="Editar" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>&nbsp;'+
					'<a href="<?= base_url() ?>admin/roles/users/'+val+'" title="Administrar usuarios para el rol" class="btn btn-warning btn-xs hidden-xs"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>&nbsp;'+
					'<a title="Eliminar" data-id="'+val+'" class="eliminar btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
				}
			}]
		});
		$('.eliminar').on('click', function(event) {
			event.preventDefault();
			var btn = $(this);
			bootbox.confirm("Desea eliminar el Rol?", function(res) {
				if (res){
					$.post('<?php echo site_url("admin/roles/ajax_delete_role") ?>', { id: btn.data('id') }, function(data) {
						bootbox.alert(data, function () {
							document.location.reload(true);
						});
					}).fail(function (data) {
						bootbox.alert(data.responseText);
					});
				};
			}); 
		});
	});
</script> 