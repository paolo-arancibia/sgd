var personas = new Bloodhound({
    remote: {
        url: 'personas/%QUERY',
        wildcard: '%QUERY'
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    datumTokenizer: Bloodhound.tokenizers.whitespace
});

var departamentos = new Bloodhound({
    remote: {
        url: 'departamentos/%QUERY',
        wildcard: '%QUERY'
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    datumTokenizer: Bloodhound.tokenizers.whitespace
});

$('#buscadorRemitentes').typeahead({
    minLength: 2,
    highlight: true,
    classNames: {
        input: 'form-control',
        suggestion: 'px-3 py-1 tt-suggestion', // tt-suggestion
        menu: 'shadow border border-primary border-top-0 rounded-bottom w-100 bg-light' //tt-menu
    }
},{
    name: 'personas',
    source: personas,
    async: true,
    limit: 7,
    templates: {
        header: '<div class="bg-secondary text-white text-center font-weight-bold px-3 py-1">Personas encontradas</div>'
    },
    display: function(obj) {
        return obj.nombre;
    }
},{
    name: 'departamentos',
    source: departamentos,
    async: true,
    limit: 7,
    templates: {
        header: '<div class="bg-secondary text-white text-center font-weight-bold px-3 py-1">Departamentos encontrados</div>'
    },
    display: function(obj) {
        return obj.descripcion;
    }
}).on('typeahead:select', function(ev, obj) {
    if( obj.tipo == 'pers' ) {
	$('#template_personanueva').hide();
        $('#template_depto').hide();

        $('#template_persona')
            .show()
            .find('h5.card-title').html(obj.nombre)
            .parent().find('h6.card-subtitle').html(obj.rut);
        $('#id-persona').val(obj.id);
    } else if( obj.tipo == 'depto' ) {
	$('#template_personanueva').hide();
        $('#template_persona').hide()
        $('#template_depto')
            .show()
            .find('h5.card-title').html(obj.descripcion)
            .parent().find('h6.card-subtitle').html( obj.encargado);
        $('#id-depto').val(obj.idDepartamento);
    }
}).focus();

$('.delete-persona').click( function() {
    $('#template_persona').hide();
    $('#template_personanueva').show();
    $('#buscadorRemitentes').typeahead('val', '');
    $('#id-persona').val('');
});

$('.delete-depto').click( function() {
    $('#template_depto').hide();
    $('#template_personanueva').show();
    $('#buscadorRemitentes').typeahead('val', '');
    $('#id-depto').val('');
});
