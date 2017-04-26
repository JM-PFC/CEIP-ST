$(document).ready(function () {

		$('#img_admin').on('mouseover', function(event) {
			$(this).addClass('hide');
			$('#img_admin_hover').removeClass('hide');
		});
		$('#img_admin_hover').on('mouseleave', function(event) {
			$(this).addClass('hide');
			$('#img_admin').removeClass('hide');
		});
		//Se activa la descripción emergente en los avisos.
		$(".barramensajes .label-as-badge").tooltip({
			placement: "bottom"
		});
				$("a").tooltip({
			placement: "bottom"
		});



});