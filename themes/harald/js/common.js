$(document).ready(function(){
	$('a[data-type="confirm"]').removeClass('delete-button');

	$('a[data-type="confirm"]').click(function(event){
		event.preventDefault();

		var obj 		= $(this);
		var msg 		= 'Please confirm!';
		var dataMessage = obj.attr('data-message');

		if( dataMessage != '' && dataMessage !== undefined ){
			msg = obj.attr('data-message');
		}

		if( confirm(msg) ){
			$(document).attr('location', obj.attr('href'));
		}
	});


	$('#page_select').on('change', function(){
		$(window).attr('location', $(this).attr('data-href') + '&page=' + $(this).val() );
	});

});
