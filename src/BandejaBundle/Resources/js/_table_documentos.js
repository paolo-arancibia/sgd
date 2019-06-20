$( function() {
    $('#selectAll').click( function() {
	if( $(this).is(':checked') )
            $('input[type=checkbox]').prop('checked',true);
	else
            $('input[type=checkbox]').prop('checked',false);
    });

    /** Mostrar acciones o filtros */
    if($('input[type=checkbox]').is(':checked')) {
	$('#filters').collapse('hide');
	$('#actions').collapse('show');
    }

    $('input[type=checkbox]').click( function() {
	var checked = false;

	if( $('input[type=checkbox]').is(':checked') ) {
            $('#filters').collapse('hide');
            $('#actions').collapse('show');
	} else {
            $('#filters').collapse('show');
            $('#actions').collapse('hide');
	}
    });

    /** ver documento */
    $('tr > td:not(:first-child)').click( function() {
	window.location.href = $(this).parent().data('href');
	return false;
    }).css('cursor','pointer');
});
