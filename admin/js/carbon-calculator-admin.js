(function( $ ) {
	'use strict';
	var events = {
		ready: function(){
			$('.carbon-output p span').each(function(){
				setInterval(function(){
					var total_savings = $(this).data('total-savings');
					var savings_per_period = $(this).data('savings-per-second') / 10;
            		$(this).html( events.add_comma((total_savings + savings_per_period).toFixed(3).toString()));
					$(this).data('total-savings', (total_savings + savings_per_period));
      			}.bind(this), 100)
			});
		},
		add_comma: function(nStr){
			nStr += '';
			var x = nStr.split('.');
			var x1 = x[0];
			var x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return "<strong>" + x1 + "</strong>" + x2;
		}
	};
	$( window ).ready(events.ready);
})( jQuery );
