<div class="container-fluid">
	<div class="row-fluid">
		<div class="navbar header-navbar">
			<div class="container-fluid">
				<?php echo anchor('admin/dashboard', 'CI Starter','class="navbar-brand"'); ?>
				<ul class="nav navbar-nav navbar-right">
					<li><?= anchor('https://www.boliviasoftware.com', '<span class="glyphicon glyphicon-globe" aria-hidden="true"></span>', array('target'=>'_blank','title'=>'Visite Bolivia Software')); ?></li>
					<li class="divider"></li>
					<li><?php echo anchor('admin/profile', '<span class="glyphicon glyphicon-user" aria-hidden="true"></span>','title="Perfil"'); ?></li>
					<li><?php echo anchor('logout', '<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>','title="Salir"'); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>