class TableroComposite {
    // TableroComposite.listado: Lista deas

    constructor(texto) {
        if (this.constructor === TableroComposite) {
            throw new TypeError('La clase TableroComposite es abstracta');
        }
        
        /**
         * Inicializamos la Propiedad Listado!
         */
        this.listado = [];
        if (texto !== undefined) {
            this.formar_palabra(texto); //letras/palabr
        }
    }

    formar_palabra(objeto) {
        if (objeto instanceof TableroComposite)
        {
           // console.log("Procesa objeto")
            var textos = objeto.listado;
            //console.log("Textos del objeto:")
            //console.log(textos)
            for (var i in textos) {
                this.formar_palabra(textos[i]);
            }
        } else if (Array.isArray(objeto)) {
            //console.log("Procesa array")
            for (var i in objeto) {
                this.formar_palabra(objeto[i]);
            }
        } else {
            //console.log("Agrega texto: "  + objeto)
            this.listado.push(objeto);
        }
        return this;
    }

    borrar() {
        this.borrarUltima();
        return this;
    }

    borrarUltima() {
        this.listado.pop();
        return this;
    }

    borrarTodo() {
        this.listado = [];
        return this;
    }

    procesar_texto() {
        return this.listado.join('');
    }
}

class Letra extends TableroComposite {
    agregar(texto) {
        if (texto.length > 1)
            throw new Error('Se debe pasar solo una letra');

        if (texto.length == 0)
            throw new Error('Se debe pasar al menos una letra');

        return TableroComposite.prototype.agregar.call(this, texto);
    }
}

class Palabra extends TableroComposite {
}

var palabraComposite = new Palabra();