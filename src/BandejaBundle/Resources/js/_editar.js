function datas(){
    return ['lalalala', 'lelelele', 'lililili', 'lolololo', 'lulululu']
}

var personas = new Bloodhound({
    local: ['lalalala', 'lelelele', 'lililili', 'lolololo', 'lulululu'],
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    datumTokenizer: Bloodhound.tokenizers.whitespace
});

$('#buscadorRemitentes').typeahead({
    minLength: 4,
    highlight: true,
    classNames: {input: 'form-control'}
},{
    name: 'personas',
    source: personas,
    async: true,
    limit: 7
});
