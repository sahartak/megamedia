function get_price_by_square(width, height, square_price) {
	var price = width * 0.01 * height * 0.01 * square_price;
	return price;
}

function additional_price(table) {
	var price = parseInt(table.find('input.add_price').val());
	if(price) {
		var total_price = 0;
		var width = 0, height = 0, amount = 0;
		table.find('tr:not(:first):not(:last)').each(function() {
			var dimensions_td = $(this).find('.add_dimensions');
			if(dimensions_td.find('input').length) {
				width = parseInt(dimensions_td.find('input.add_width').val());
				height = parseInt(dimensions_td.find('input.add_height').val());
			} else {
				var dimensions = dimensions_td.text().split(' x ');
				width = dimensions[0];
				height = dimensions[1];
			}

			var amount_td = $(this).find('.add_amount');
			if(amount_td.find('input').length) {
				amount = parseInt(amount_td.find('input').val());
			} else {
				amount = parseInt(amount_td.text());
			}
			if(width && height && amount) {
				total_price += amount * get_price_by_square(width, height, price);
			}
		});
		total_price = Math.round(total_price);
		table.closest('.checkout_block').find('.total_price').text(total_price);
		get_full_price();
	}
}

function get_full_price() {
	var full_price = 0;
	$('.total_price').each(function() {
		var price = parseInt($(this).text());
		if(price) {
			full_price += price;
		}
	});
	if(full_price == 0) {
		window.location.reload();
	}
	$('#full_price').text(full_price);
}

$(document).ready(function () {
	var b = 1, p = 1, r = 1;
	if(parseInt($('#banners_count').val()) > 0) {
		b = parseInt($('#banners_count').val());
	}
	if(parseInt($('#posters_count').val()) > 0) {
		p = parseInt($('#posters_count').val());
	}
	if(parseInt($('#rollups_count').val()) > 0) {
		r = parseInt($('#rollups_count').val());
	}
	var banner_tab =  $("#banner_template").html();
	var poster_tab =  $("#poster_template").html();
	var rollups_tab = $("#rollups_template").html();
	var table_inner = $("#additional_row").html();

	$("#add_banner").click(function () {
		b++;
		var text = $('.nav-tabs').html();
		$(this).closest('li').before("<li><a data-toggle='tab' href='#banner_"+b+"'> Banner "+b+"  </a></li>") ;
		var n_banner_tab = "<div id='banner_"+b+"' class='tab-pane'>" + banner_tab + "</div>";
		$(".tab-content").append(n_banner_tab);
		$('#banner_'+b).find('input, select').each(function(){
			var name = 'banners['+b+']' + $(this).attr('name');
			$(this).attr('name', name);
		});
		$('.nav-tabs li.active').removeClass('active');
		$('a[href="#banner_'+b+'"]').parent().addClass('active');
		$('.tab-content .tab-pane.active').removeClass('active');
		$('#banner_'+b).addClass('active');
	});

	$("#add_poster").click(function () {
		p++;
		var text = $('.nav-tabs').html();
		$(this).closest('li').before("<li><a data-toggle='tab' href='#poster_"+p+"'> Poster "+p+"  </a></li>") ;
		var n_poster_tab = "<div id='poster_"+p+"' class='tab-pane'>" + poster_tab + "</div>";
		$(".tab-content").append(n_poster_tab);
		$('#poster_'+p).find('input, select').each(function(){
			var name = 'posters['+p+']' + $(this).attr('name');
			$(this).attr('name', name);
		});
		$('.nav-tabs li.active').removeClass('active');
		$('a[href="#poster_'+p+'"]').parent().addClass('active');
		$('.tab-content .tab-pane.active').removeClass('active');
		$('#poster_'+p).addClass('active');
	});

	$("#add_rollups").click(function () {
		r++;
		var text = $('.nav-tabs').html();
		$(this).closest('li').before("<li><a data-toggle='tab' href='#rollups_"+r+"'> Roll ups "+r+"  </a></li>") ;
		var n_rollups_tab = "<div id='rollups_"+r+"' class='tab-pane'>" + rollups_tab + "</div>";
		$(".tab-content").append(n_rollups_tab);
		$('#rollups_'+r).find('input, select').each(function(){
			var name = 'rollups['+r+']' + $(this).attr('name');
			$(this).attr('name', name);
		});
		$('.nav-tabs li.active').removeClass('active');
		$('a[href="#rollups_'+r+'"]').parent().addClass('active');
		$('.tab-content .tab-pane.active').removeClass('active');
		$('#rollups_'+r).addClass('active');
	});

	$('body').on('click','.btn-custom',function(){
		$('#table').append("<tr>"+table_inner+"</tr>");
		$(this).remove();
	});


	$('body').on('change','.col1',function(){
		var list =  $(this).closest('div').find('.col1');
		var  total = 0;
		list.each(function(){
			total += parseInt($(this).val()) || 0;
		});
		$(this).closest('div').find('.total_col1').text(total);
	});

	$('body').on('change','.col2',function(){
		var list =  $(this).closest('div').find('.col2');
		var  total = 0;
		list.each(function(){
			total += parseInt($(this).val()) || 0;
		});
		$(this).closest('div').find('.total_col2').text(total);
	});

	$('body').on('change','.col3',function(){
		var list =  $(this).closest('div').find('.col3');
		var  total = 0;
		list.each(function(){
			total += parseInt($(this).val()) || 0;
		});
		$(this).closest('div').find('.total_col3').text(total);
	});

	$('body').on('change','.col4',function(){
		var list =  $(this).closest('div').find('.col4');
		var  total = 0;
		list.each(function(){
			total += parseInt($(this).val()) || 0;
		});
		$(this).closest('div').find('.total_col4').text(total);
	});

	$("#additional_form").submit(function () {
		var list = $(this).children('table').find('tr');
		var _return = true;
		list.each(function(){
			$(this).find('.number').each(function () {
				var value = parseInt($(this).val()) || 0;
				if(!value){
					$(this).addClass('error');
					_return =  false;
				}
				else {
					$(this).removeClass('error');
				}
			});
		});
		return _return;
	});

	$(".nav-tabs").click(function () {
		$('.tab-content').fadeOut(100).fadeIn(100);
	});

	$('body').on('change', '.store_selecting', function() {
		var store_id = $(this).val();
		var options = $('#store_'+store_id).html();
		$(this).closest('tr').find('.ophaeng_select').html(options);
	});

	$('.delete_campaign').click(function() {
		if(!confirm('Are you sure trash this item?')) {
			return false;
		}
		var campaign = $(this).attr('data-campaign');
		$.post('/test/delete_campaign', {campaign: campaign});
		$('td.campaign_'+campaign+',th.campaign_'+campaign).remove();
		window.location.reload();
	});

	$('.edit_campaign').click(function() {
		var campaign = $(this).attr('data-campaign');
		$(this).toggleClass('camp_editing');
		if($(this).hasClass('camp_editing')) {
			$('td.campaign_'+campaign+'.campaign_edit').each(function() {
				var amount = parseInt($(this).text());
				var input = '<input type="number" min="0" value="'+amount+'" />';
				$(this).html(input);
			});
		} else {
			$('td.campaign_'+campaign+'.campaign_edit').each(function() {
				var amount = $(this).find('input').val();
				$(this).html(amount);
			});
		}
	});

	$('body').on('change', '.campaign_edit input', function() {
		var amount = $(this).val();
		var campaign = $(this).parent().attr('data-campaign');
		var id = $(this).parent().attr('data-id');
		var type = $(this).parent().attr('data-type');
		var data = { amount: amount, campaign: campaign, id: id, type: type };
		$.post( '/test/update_amount', data );
		var total = 0;
		$('td.campaign_'+campaign+'.campaign_edit[data-type='+type+'] input').each(function() {
			total += parseInt($(this).val());
		});
		$('td.campaign_'+campaign+'.s_type_'+type).text(total);
		var price = 0;
		$('td.campaign_'+campaign+'.s_totals').each(function() {
			var count = parseInt($(this).text());
			price += parseInt(parseFloat($(this).attr('data-price')) * count);
		});
		$('td.campaign_'+campaign+'.campaign_price').text(price);
		var total_price = 0;
		$('.campaign_price').each(function() {
			total_price += parseInt($(this).text());
		});
		$(this).closest('.checkout_block').find('.total_price').text(total_price);
		get_full_price();
	});

	$('body').on('change', 'input.edit_week', function() {
		var week = $(this).val();
		if(week != '') {
			var type = $(this).attr('data-type');
			var data = { week: week, type: type };
			$.post( '/test/update_week', data );
		}
	});

	$('body').on('click', '.del_tab', function() {
		var div_id = $(this).closest('.tab-pane').attr('id');
		var id = parseInt(div_id.substr(div_id.indexOf('_')+1));
		var type = $(this).attr('data-type');
		$('#'+type+'_'+id).remove();
		var li = $('ul.nav-tabs a[href="#'+type+'_'+id+'"]').closest('li');
		li.prev().find('a').trigger('click');
		li.remove();
	});

	$('body').on('click', '.additional_del', function () {
		var id = parseInt($(this).attr('data-id'));
		var data = { id: id };
		$.post( '/test/delete_additional', data );
		var table = $(this).closest('table');
		$(this).closest('tr').remove();
		var total = 0;
		table.find('tr:not(:first):not(:last)').each(function() {
			var td = $(this).find('td:eq(2)');
			if(td.find('input').length) {
				var amount = parseInt(td.find('input').val());
			} else {
				var amount = parseInt(td.text());
			}
			total += amount;
		});
		table.find('.add_total').text(total);
		additional_price(table);
	});

	$('body').on('click', '.additional_edit', function() {

		$(this).toggleClass('add_editing');
		var tr = $(this).closest('tr');
		if($(this).hasClass('add_editing')) {

			var amount = parseInt(tr.find('.add_amount').text());
			tr.find('.add_amount').html('<input type="number" min="0" name="amount" class="small_input" value = "'+amount+'" />');

			var dimensions = tr.find('.add_dimensions').text().split(' x ');
			var dim_str = '<input type="number" min="0" name="width" class="small_input add_width" value = "'+dimensions[0]+'" /> ' +
							'x <input type="number" min="0" name="height" class="small_input add_height" value = "'+dimensions[1]+'" />' ;
			tr.find('.add_dimensions').html(dim_str);

			var store_id = tr.find('.add_store').attr('data-id');
			var hanging_methods = $('#store_'+store_id).html();
			if(hanging_methods) {
				var cur_hanging = tr.find('.add_hanging').text();
				tr.find('.add_hanging').html('<select name="hanging">'+hanging_methods+'</select>').find('option:contains("'+cur_hanging+'")').prop('selected', true);
			}

			var materials = $(this).closest('.checkout_block').find('.material').html();
			if(materials) {
				var cur_material = tr.find('.add_materials').text();
				tr.find('.add_materials').html(materials).find('select').attr('name', 'material').find('option:contains("'+cur_material+'")').prop('selected', true);
			}

		} else {

			var amount = tr.find('.add_amount input').val();
			tr.find('.add_amount').html(amount);

			var width = tr.find('.add_width').val();
			var height = tr.find('.add_height').val();
			var dimension = width + ' x ' + height;
			tr.find('.add_dimensions').html(dimension);

			var hanging = tr.find('.add_hanging');
			if(hanging) {
				var hanging_method = hanging.find('option:selected').text();
				hanging.html(hanging_method);
			}

			var material = tr.find('.add_materials');
			if(material) {
				var material_option = material.find('option:selected').text();
				material.html(material_option);
			}
		}
	});

	$('.additional_checkout').on('change', 'input, select', function() {
		var field = $(this).attr('name');
		var value = $(this).val();
		var id = parseInt($(this).closest('tr').attr('data-id'));
		if(field && value && id) {
			$.post('/test/update_field', {field: field, value: value, id: id});
		}
	});

	$('.additional_checkout').on('change', '.add_amount input', function () {
		var total = 0;
		var table = $(this).closest('table');
		table.find('.add_amount').each(function() {
			if($(this).find('input').length) {
				var amount = parseInt($(this).find('input').val());
			} else {
				var amount = parseInt($(this).text());
			}
			if(amount) {
				total += amount;
			}
		});
		table.find('.add_total').text(total);
		additional_price(table);
	});

	$('.additional_checkout').on('change', '.add_dimensions input', function() {
		var table = $(this).closest('table');
		additional_price(table);
	});

});
