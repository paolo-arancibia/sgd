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
    if (obj.tipo == 'pers') {
	$('#template_personanueva')
            .hide()
            .find(':input[name^="persona"]').each( function() {
                $(this).removeAttr('required');
            });

        $('#template_depto').hide();

        $('#template_persona')
            .show()
            .find('h5.card-title').html(obj.nombre)
            .parent().find('h6.card-subtitle').html(obj.rut);

        $('#remitente_id_persona').val(obj.id);
        $('#remitente_id_depto').val('');
    } else if (obj.tipo == 'depto') {
	$('#template_personanueva')
	    .hide()
            .find(':input[name^="persona"]').each( function() {
                $(this).removeAttr('required');
            });

        $('#template_persona').hide()

        $('#template_depto')
            .show()
            .find('h5.card-title').html(obj.descripcion)
            .parent().find('h6.card-subtitle').html(obj.encargado);

        $('#remitente_id_persona').val('');
        $('#remitente_id_depto').val(obj.idDepartamento);
    }
}).focus();

$('.delete-persona').click( function() {
    $('#template_persona').hide();

    $('#template_personanueva')
        .show()
        .find(':input[name^="persona"]').each( function() {
            $(this).attr('required', 'required');
        });

    $('#buscadorRemitentes').typeahead('val', '');

    $('#remitente_id_depto').val('');
    $('#remitente_id_persona').val('');
});

$('.delete-depto').click( function() {
    $('#template_depto').hide();

    $('#template_personanueva')
        .show()
        .find(':input[name^="persona"]').each( function() {
            $(this).attr('required', 'required');
        });

    $('#buscadorRemitentes').typeahead('val', '');

    $('#remitente_id_depto').val('');
    $('#remitente_id_persona').val('');
});


function validarRUT(cadena){ // function que valida el RUT ingresado

	var rut = cadena.split("").reverse().join(""); // la cadena se da vuelta ejemplo 123 a 321

	var aux = 1; // inicializamos la variable en 1

  var suma = 0; // inicializamos la variable en 0

	for(i=0;i<rut.length;i++){ // recorremos el rut y medidimos su longitud

		aux++; // auxiliar se le suma 1 (2)

		suma += parseInt(rut[i])*aux; // cada numero de la cadena se multiplica por 2.

		if(aux == 7){
		    aux = 1;
		}
	}

	digit = 11-suma%11;

	if(digit == 11){ // si es el resultado es 11 el digito verificador es 0
		d = "0";
	}
	else if(digit == 10){  // si el resultado es 10 el digito verificador es K
		d = "K";
	}
	else{
	  d = digit;
	} //*/
	return d; // devolvemos el digito verificador para utilizarlo al comprobarlo con el digito del $_POST['digito']
}

$('#persona_rut').keyup(function(){
    var dv = validarRUT($(this).val());

    $('#persona_dv').val(dv);
});
