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
