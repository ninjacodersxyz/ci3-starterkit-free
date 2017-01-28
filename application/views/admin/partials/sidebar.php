<ul class="list-unstyled" id="sidebar-menu">
	<li <?= ($this->uri->segment(2) == 'dashboard') ? 'class="active"' :'' ?>><?= anchor('admin/dashboard', '<span class="glyphicon glyphicon-th" aria-hidden="true"></span> Dashboard'); ?></li>
	<li <?= ($this->uri->segment(2) == 'usuarios') ? 'class="active"' :'' ?>><?= anchor('admin/usuarios', '<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Usuarios'); ?></li>
	<li <?= ($this->uri->segment(2) == 'roles') ? 'class="active"' :'' ?>><?= anchor('admin/roles', '<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Roles'); ?></li>
	<li <?= ($this->uri->segment(2) == 'permisos') ? 'class="active"' :'' ?>><?= anchor('admin/permisos', '<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Permisos'); ?></li>
	<!-- Slicknav only menu elements -->
	<li class="header-hide"><?php echo anchor('admin/profile', '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Perfil','title="Perfil"'); ?></li>
	<li class="header-hide"><?php echo anchor('logout', '<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Salir','title="Salir"'); ?></li>
	<li class="header-hide"><?= anchor('https://www.boliviasoftware.com', '<span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Bolivia Software', array('target'=>'_blank','title'=>'Visite Bolivia Software')); ?></li>
</ul>
