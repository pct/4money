$(function() {
    $('#print').click(function() {
        $('#main').height('');
        window.print();
    });
	$.number_format = function(number, decimals, dec_point, thousands_sep) {
		// Formats a number with grouped thousands  
		// http://phpjs.org/functions/number_format
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function (n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	}

    $.count_prices = function() {
        var sub_total_price = 0;
        var vat = 0.05;
        var vat_price = 0;
        var total_price = 0;
        
        $('.item_price').each(function(i, el) {
            $.item_quantity = $(el).parent().find('.item_quantity');
			$.item_price = $(el).parent().find('.item_price');
            $.item_sub_total = $(el).parent().find('.item_sub_total');
            var item_quantity = (isNaN(parseFloat($.item_quantity.html()))) ? 0 : parseFloat($.item_quantity.html());
            var item_price = (isNaN(parseFloat($(el).html()))) ? 0 : parseFloat($(el).html());
            var tmp_value = (item_quantity * item_price).toFixed(2);
            $.item_sub_total.html($.number_format(tmp_value));
			      $.item_price.html(item_price.toLocaleString());
        });
    }
    // 啟動時先算第一次
    $.count_prices();

    // 補0
    $.pad2 = function(number) {
        return (number < 10 ? '0' : '') + number;
    }

	$.item_id = function(){
		var id=1;
		$('#item_list tr').each(function(i,el){
			var item_id=$(':first-child',el);
			if(item_id.html()!=''){
				id=1;
				return true;
			}
			item_id.html($.pad2(id++));
		});
	}
	$.item_id();
    //畫面寬度過小自動隱藏menu
    if($(document).width()<1200){
        hide_menu()
    }
});
