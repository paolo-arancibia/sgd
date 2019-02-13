var personas = new Bloodhound({
    remote: {
        url: 'personas/%QUERY',
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
    display: function(obj){
        return obj.nombre + ' ' + obj.rut;
    }
}).on('typeahead:select', function(ev) {
    $('#template_personanueva').hide();
});
