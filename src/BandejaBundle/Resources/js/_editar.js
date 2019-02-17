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
    classNames: {input: 'form-control'}
},{
    name: 'personas',
    source: personas,
    async: true,
    limit: 7,
    display: function(obj) {
        return obj.nombre;
    }
},{
    name: 'departamentos',
    source: departamentos,
    async: true,
    limit: 7,
    display: function(obj) {
        return obj.descripcion;
    }
}).on('typeahead:select', function(ev, obj) {
    if( obj.tipo == 'pers' ) {
	$('#template_personanueva').hide();
        $('#template_persona').show()
        $('#template_depto').hide()
    } else if( obj.tipo == 'depto' ) {
	$('#template_personanueva').hide();
        $('#template_persona').hide()
        $('#template_depto').show()
    }
});
