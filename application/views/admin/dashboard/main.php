<h2 class="page-title">Dashboard del sistema</h2>
<div id="dashboard">
	<div class="row">
		<div class="col-md-6">
			<div class="new-well">
				<h3 class="panel-title">Ingresos por usuario al sistema (últimos 5 días registrados)</h3>
				<div class="ct-chart ct-chart1"></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="new-well">
				<h3 class="panel-title">Ingresos fallidos al sistema (últimos 5 días registrados)</h3>
				<div class="ct-chart ct-chart2"></div>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-8">
			<div class="new-well chats">
				<h3 class="panel-title">Mensajería interna</h3>
				<div class="mensajes clearfix"></div>
				<div class="formulario">
					<div class="row">
						<div class="col-md-10">
							<form>
								<input type="text" class="mensaje-chat form-control" placeholder="Ingrese su mensaje">
							</form>
						</div>
						<div class="col-md-2">
							<a class="btn btn-primary btn-block enviar-chat">Enviar</a> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="new-well publicidad">
				<p class="text-center">
					<a href="https://www.boliviasoftware.com/" target="_blank"><img src="assets/imgs/boliviasoftware.png" alt="Bolivia Software logo" class="boliviasoftware-logo hidden-sm"></a>
				</p>
				<h4>En Bolivia Software le brindamos:</h4>
				<ul>
					<li>Diseño de logotipos.</li>
					<li>Diseño Web personalizado.</li>
					<li>Diseño de páginas web corporativas.</li>
					<li>Sistemas contables a medida.</li>
					<li>Sistemas académicos a medida.</li>
					<li><a href="https://www.boliviasoftware.com/contactenos/" target="_blank">Contáctenos</a> para saber de otros desarrollos que pueden hacer mas productivo su empresa o su emprendimiento</li>
				</ul>
			</div>
			<div class="new-well publicidad">
				<h4>La versión premium de este sistema ademas de las funcionales actuales tiene:</h4>
				<ul>
					<li>Integracion con <a href="https://bower.io/" target="_blank">Bower</a></li>
					<li>Desarrollo de los estilos CSS con <a href="http://lesscss.org/" target="_blank">LESS</a></li>
					<li>Servicio de Instalación en servidores privados o compartidos.</li>
					<li>Manuales de usuario para el uso del sistema</li>
					<li>Capacitación sobre como agregar módulos al sistema</li>
					<li>Optimización y compresión de la salida de datos para tener una navegación mas rápida</li>
					<li>Muchas otras mas características para hacer mas productivo su requerimiento</li>
					<li><a href="https://www.boliviasoftware.com/contactenos/" target="_blank">Contáctenos</a> para saber como adquirir la versión premium sistema</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		//CHART Ingresos correctos
		var data1 = {
			labels: [],
			series: [[]]
		};
		$.each($.parseJSON('<?= $ingresos_correctos ?>'), function(idx,item) {
			var d = new Date(item.fecha_ingreso);
			data1.labels.push(d.getDate() + '/'+ (d.getMonth()+1));
			data1.series[0].push(parseInt(item.ct));
		});
		new Chartist.Bar('.ct-chart1', data1, { fullWidth: true});

		// CHART ingresos fallidos
		var data2 = {
			labels: [],
			series: [[]]
		};
		$.each($.parseJSON('<?= $ingresos_fallidos ?>'), function(idx,item) {
			var d = new Date(item.date);
			data2.labels.push(d.getDate() + '/'+ (d.getMonth()+1));
			data2.series[0].push(parseInt(item.ct));
		});
		new Chartist.Bar('.ct-chart2', data2, { fullWidth: true});

		// CHAT
		var initialData=null;
		function buildChat(data, toTop) {
			if (data.length) {
				var html = '<div class="chat-list">';
				$.each(data, function(idx, v) {
					html+='<div class="bubble '+((v.owner) ? 'owner' : 'other') + '">';
					html+='<h5 class="usuario">'+v.usuario+' - en '+v.fecha+'</h5>';
					html+='<p>'+v.mensaje+'</p>';
					html+='</div>';
				});
				html+='</div>';
				$('.mensajes').html(html).animate({scrollTop:0},700);
			}
		};

		function getChats() {
			$.get('<?= base_url() ?>admin/dashboard/ajax_get_chat_messages', function(data) {
				if (initialData != data) {
					initialData = data;
					buildChat($.parseJSON(data));
				};
			}).fail(function (err) {
				clearInterval(interval);
				$('.mensajes').html('<h1>El sistema de mensajería no se encuentra disponible</h1>');
				$('.formulario').remove();
			});
		};

		getChats();
		var interval = setInterval(getChats, 3e4);

		$('.enviar-chat').click(function(event) {
			event.preventDefault();
			$.post('<?= base_url() ?>admin/dashboard/ajax_add_chat_message', {message: $('.mensaje-chat').val()}, function(data, textStatus, xhr) {
				$('.mensaje-chat').val('');
				buildChat($.parseJSON(data));
			});
		});

	});
</script>
