(function(){

	$.submitGate = {

		init : function(){

			$('.submit-gate-protected').each(function(){

				var form = $(this).closest('form'),
					button = this;

				$(this).closest('form').find('.submit-gate').each(function(){
					$(this).on('change', function(){

					});
				});

			});
		}
	};

})(jQuery);