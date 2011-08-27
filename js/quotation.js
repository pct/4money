$(function() {
    // item 數量
    $.item_num = 1;

    // if quotation_edit 
    $.is_match = location.href.match(/quotation_edit/);
    if ($.is_match) {
        $.item_num = parseInt($('#item_table tbody tr td:last').attr('rel')) + 1;
    }

    // bind add_item
    $('#add_item').click(function() {
        $.item_num++;
        $('#item_tpl')
            .clone()
            .attr('id','')
            .find(':input[name=items_desc]').attr('name', 'items['+ $.item_num +'][desc]').end()
            .find(':input[name=items_quantity]').attr('name', 'items['+ $.item_num +'][quantity]').end()
            .find(':input[name=items_price]').attr('name', 'items['+ $.item_num +'][price]').end()
            .find(':input[name=items_notes]').attr('name', 'items['+ $.item_num +'][notes]').end()
            .appendTo('#item_table tbody')
            .show();
    });

    // bind remove_item 
    $('.remove_item').live('click', function() {
        $(this).parent().parent().remove();
        $.count_prices();
    });

    // count price
    $('.item_price').live('change', function() {
        $.count_prices();
    });

    $('.item_quantity').live('change', function() {
        $.count_prices();
    });

    $.count_prices = function() {
        var sub_total_price = 0;
        var vat = 0.05;
        var vat_price = 0;
        var total_price = 0;
        
        $('.item_price').not(':input[name=items_price]').each(function(i, el) {
            $.item_quantity = $(el).parent().parent().find('.item_quantity')[0];
            $.item_sub_total = $(el).parent().parent().find('.item_sub_total');
            var item_quantity = (isNaN(parseFloat($.item_quantity.value))) ? 0 : parseFloat($.item_quantity.value);
            var item_price = (isNaN(parseFloat(el.value))) ? 0 : parseFloat(el.value);
            var tmp_value = (item_quantity * item_price).toFixed(2);
            sub_total_price += tmp_value;
            $.item_sub_total.html(tmp_value);
        });

        vat_price = Math.round(sub_total_price * vat);
        total_price = (sub_total_price + vat_price);

        $('#sub_total_price').html(sub_total_price.toFixed(2));
        $('#vat_price').html(vat_price.toFixed(2));
        $('#total_price').html(total_price.toFixed(2));

        $(':input[name=sub_total_price]').val(sub_total_price.toFixed(2));
        $(':input[name=vat_price]').val(vat_price.toFixed(2));
        $(':input[name=total_price]').val(total_price.toFixed(2));
    }

    // 啟動時先算第一次
    $.count_prices();
});
