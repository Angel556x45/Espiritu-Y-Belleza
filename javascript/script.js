const lista_articulos = document.getElementById('articulos');
const lista_etiquetas = document.getElementById('etiquetas');
const lista_comentarios = document.getElementById('comentarios');
const lista_discusiones = document.getElementById('discusiones');

let urlA = '../json/articulo.json';
let urlE = '../json/etiqueta.json';
let urlC = '../json/comentarios.json';
let urlD = '../json/discusion.json';
let urlEvento = '../json/evento.json';
let urlDatosUsuario = '../json/DatosUsuario.json'
let urlTodoslosUsuarios = "../json/todos_los_usuarios.json";

let btnNext;
let btnPrevious;


window.addEventListener("load", (event) => {

  comprobar_campos_crear_cuenta();
  if (window.location.href.indexOf("index.php") != -1) {
    pintar_usuarios_para_mensaje(true);
  } else {
    pintar_usuarios_para_mensaje(false);
  }

  if (window.location.href.indexOf("control_articulos.php") != -1) {
    pintarEtiquetas();
    pintarArticulos();
  } else if (window.location.href.indexOf("control_articuloEditar.php") != -1) {
    previewBeforeUpload("portada");
    previewBeforeUpload("file-0");
    previewBeforeUpload("file-1");
    previewBeforeUpload("file-2");
    previewBeforeUpload("file-3");
    previewBeforeUpload("file-4");
    previewBeforeUpload("file-5");

  } else if (window.location.href.indexOf("control_discusiones.php") != -1) {
    pintarEtiquetas();
    pintarDiscusiones();
  } else if (window.location.href.indexOf("control_articuloLeer.php") != -1) {
    pintarComentarios(true);
  } else if (window.location.href.indexOf("control_discusionLeer.php") != -1) {
    pintarComentarios(false);
  } else if (window.location.href.indexOf("control_eventoEditar.php") != -1) {
    pintarLocalizacionFormulario();
    previewBeforeUpload("portada");
  } else if (window.location.href.indexOf("control_eventoLeer.php") != -1) {
    pintar_datos_tiempo();
  } else if (window.location.href.indexOf("control_usuario.php") != -1) {
    pintar_usuarios();
  } 

  
})

function nombre_tipos_de_usuario (tipo) {
  let nombre= ['placeholder', 'bg-danger'];
  switch (tipo) {
      case 0:
          nombre=['Administrador', 'bg-danger'];
          break;
      case 1:
          nombre=['Moderador', 'bg-info'];
          break;
      case 2:
          nombre=['Predicador', 'bg-success'];
          break;
      case 3:      
          nombre=['Laico', 'bg-dark'];
          break;    
  }
  return nombre;
}

function estadoUsuario(estado) {
  if (estado==1) {
    return ["Activo", "table-secondary"];
  } else {
    return ["suspendido", "table-primary"];
  }
}

function recargar_iframe_mensajes() {
  window.frames["mensajes"][1].src =  window.frames["mensajes"][1].src;
}

function previewBeforeUpload(id){
  document.querySelector("#"+id).addEventListener("change",function(e){
    if(e.target.files.length == 0){
      return;
    }
    let file = e.target.files[0];
    let url = URL.createObjectURL(file);
    document.querySelector("#"+id+"-preview").src = url;
  });
}

async function pintarEtiquetas() {
  lista_etiquetas.innerHTML=``;

  let datos = await fetch (urlE);
  let resultados = await datos.json();

  resultados.forEach(etiqueta => {

    lista_etiquetas.innerHTML += `
    
      <div class="form-check m-1">
        <input class="form-check-input" type="checkbox" name='${etiqueta.id}' id="${etiqueta.id}">
        <label class="form-check-label" for="${etiqueta.id}">
          ${etiqueta.nombre}
        </label>
      </div>
    
    `
  })
}

async function pintarArticulos(){

    lista_articulos.innerHTML=``;

    let datos = await fetch (urlA);
    let resultados = await datos.json();

    if (Object.keys(resultados).length==0) {
      lista_articulos.innerHTML = `<div class='col-md-10 text-center'>
      <p> No exsisten articulos...</p>
    </div>`
    } else {
      resultados.forEach(articulo => {

        let tipo = `<span class="badge rounded-pill bg-info d-flex align-items-center"> Artículo comunitario </span>`

        if (articulo.tipo==1) {
          tipo = `<span class="badge rounded-pill bg-success d-flex align-items-center"> Artículo oficial </span>` 
        } 
  

        lista_articulos.innerHTML += `
        
            <div class="col-md-4">

                <div class="card mb-3">
                  <h3 class="card-header text-center header-eventos d-flex align-items-center justify-content-center">${articulo.titulo}</h3>

                  <div class="foto_card" style="background-image: url(../imagenes/fotos_articulos/${articulo.id}/${articulo.portada});">
                  </div>

                  <div class="card-body text-center">
                    <form action="./control_articuloLeer.php" method="GET">
                      <button type="submit" name="ver" value="${articulo.id}" class="btn btn-outline-info"> LEER </button>
                    </form>
                  </div>
                  <div class="card-footer text-muted d-flex justify-content-between">
                    ${articulo.fechaEspana}
                    ${tipo}
                  </div>
                </div>

            </div>
        
        `
      });
    }

}

async function pintarComentarios(lugar) {
  lista_comentarios.innerHTML=``;
  
  let datos = await fetch (urlC);
  let resultados = await datos.json();

  //Aqui sacamos el Id y el tipo del usuario registrado
  datos = await fetch (urlDatosUsuario);
  let usuario = await datos.json();

  if (Object.keys(resultados).length==0) {
    lista_comentarios.innerHTML = `<div class='col-md-10 text-center'>
        <p> Aún no hay comentarios...</p>
      </div>`
  } else  {
    resultados.forEach (comentario => {
      let borrar = "";
  
      //Si el usuario registrado ha escrito este comentario, nos mostrara un boton para borrarlo
      //Tambien si es admin o moderador
      if (usuario[0] == comentario.id_usuario || usuario[1]==0 || usuario[1]==1) {
        if (lugar) {
          borrar = `
          <form action="./control_articuloLeer.php" method="POST">
            <button type='submit' name='borrar_comentario' class='btn-close'></button>
  
            <input type="hidden" name="id_usuario" value="${comentario.id_usuario}">
            <input type="hidden" name="id_tabla" value="${comentario.id_tabla}">
            <input type="hidden" name="fecha" value="${comentario.fecha}">

          </form>
        `
        } else {
          borrar = `
          <form action="./control_discusionLeer.php" method="POST">
            <button type='submit' name='borrar_comentario' class='btn-close'></button>
  
            <input type="hidden" name="id_usuario" value="${comentario.id_usuario}">
            <input type="hidden" name="id_tabla" value="${comentario.id_tabla}">
            <input type="hidden" name="fecha" value="${comentario.fecha}">

          </form>
        `
        }

      } 

      let tipo = nombre_tipos_de_usuario(comentario.tipo_usuario);

      let nombre_tipo = tipo[0]
      let nombre_clase = tipo[1]

      lista_comentarios.innerHTML += `
  
        <div class="col-md-8">
          <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                  <p> ${comentario.texto} </p>
                  ${borrar}
                </div>
                
                <div class="d-flex justify-content-between">
                  <div class="d-flex flex-row align-items-center">
                      <img src="../imagenes/foto_usuario/${comentario.foto_usuario}" alt="avatar" width="25" height="25">
                      <p class="small mb-0 ms-2">${comentario.nombre_usuario}</p>
                      <div class=" d-flex mb-0 ms-2">
                          <span class="badge ${nombre_clase}"> ${nombre_tipo} </span>
                      </div>
                  </div>
                  <div class="d-flex flex-row align-items-center">
                      <p class="small text-muted mb-0">${comentario.fecha_desde }</p>
                  </div>
                </div>
            </div>
          </div>
        </div>
      
      `;
    })
  }


}

async function pintarDiscusiones() {

  lista_discusiones.innerHTML=``;

  let datos = await fetch (urlD);
  let resultados = await datos.json();

  if (Object.keys(resultados).length==0) {
    lista_discusiones.innerHTML = `<div class='col-md-10 text-center'>
        <p> Aún no hay discusiones...</p>
      </div>`
  } else {
    resultados.forEach(discusion => {

      lista_discusiones.innerHTML += `

          <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3>${discusion.titulo}</h3>
                    <form action="./control_discusionLeer.php" method="GET">
                    <button type="submit" name="ver" value="${discusion.id}" class="btn btn-outline-info"> VER MÁS </button>
                    </form>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>${discusion.fechaEspana}</span>
                </div>
            </div>
          </div>
      `
    });
  }
}

async function pintarLocalizacionFormulario() {

  const urlProvincias = "https://www.el-tiempo.net/api/json/v2/provincias";
  let urlMunicipios = "https://www.el-tiempo.net/api/json/v2/provincias/04"

  let lista_provincias = document.getElementById('listaProvincias');
  let lista_municipios = document.getElementById('listaMunicipios');

    let datos = await fetch (urlProvincias);
    let resultados = await datos.json();

    resultados.provincias.forEach(e => {
      lista_provincias.innerHTML += `
        <option value="${e.CODPROV}">${e.NOMBRE_PROVINCIA}</option>
      `
    })

    lista_provincias.onchange = async function() {
      urlMunicipios = "https://www.el-tiempo.net/api/json/v2/provincias/"+lista_provincias.value
      console.log(urlMunicipios)
      lista_municipios.innerHTML = "<option disabled default>No hay datos...</option>"
      let datos2 = await fetch (urlMunicipios);
      let resultados2 = await datos2.json();
    
      let primero = true;
      resultados2.ciudades.forEach(async e => {

        if (primero) {
          lista_municipios.innerHTML = "";
        }

        let urlMunicipioFix = "https://www.el-tiempo.net/api/json/v2/provincias/"+lista_provincias.value+"/municipios/"+e.id
        let datos3 = await fetch (urlMunicipioFix);
        let origin = await datos3.json();
    
        lista_municipios.innerHTML += `
        
          <option value="${e.id}">${origin.municipio.NOMBRE}</option>
        
        `
        primero = false;
      })
      
    }

    let datos2 = await fetch (urlMunicipios);
    let resultados2 = await datos2.json();
    resultados2.ciudades.forEach(async e => {
      let urlMunicipioFix = "https://www.el-tiempo.net/api/json/v2/provincias/"+lista_provincias.value+"/municipios/"+e.id
      let datos3 = await fetch (urlMunicipioFix);
      let origin = await datos3.json();

      lista_municipios.innerHTML += `
      
        <option value="${e.id}">${origin.municipio.NOMBRE}</option>
      
      `
    })
  




}

async function pintar_datos_tiempo() {

  let datos1 = await fetch (urlEvento);
  let evento = await datos1.json();

  let id_provincia = evento.lugar.substring(2,-1);
  let id_municipio = evento.lugar;

  let urlMunicipio = "https://www.el-tiempo.net/api/json/v2/provincias/"+id_provincia+"/municipios/"+id_municipio

  let lugar = document.getElementById('lugar');
  let tiempo = document.getElementById('tiempo');

    let datos2 = await fetch (urlMunicipio);
    let resultados = await datos2.json();

    lugar.innerHTML = resultados.municipio.NOMBRE_PROVINCIA + "/" +resultados.municipio.NOMBRE;

    tiempo.innerHTML = `
      <i class="fa fa-cloud"> ${resultados.stateSky.description} </i>
      <br>
      <i class="fa fa-thermometer"> ${resultados.temperatura_actual} Cº </i>
    `;


}

async function comprobar_campos_crear_cuenta() {

  let datos = await fetch (urlTodoslosUsuarios);
  let resultados = await datos.json();

  let nick_usuario = document.getElementById('nickname');
  let email_usuario = document.getElementById('email');
  let pass_usuario =  document.getElementById('password');
  let pass_confirmar =  document.getElementById('passwordConfirm');

  let boton_submit = document.getElementById('send');

  let nickHelp = document.getElementById('nickHelp');
  let emailHelp = document.getElementById('emailHelp');
  let passHelp = document.getElementById('passHelp');

  let nicks = [];
  let emails = [];

  resultados.forEach(usuario => {
      nicks.push(usuario.nick);
      emails.push(usuario.email);
  });

  nick_usuario.addEventListener('keyup', e=> {
    busqueda1 = e.target.value;
    console.log(busqueda1)
    let found = nicks.find((element) => element == busqueda1);
    if (found != undefined) {
      boton_submit.className = ("btn btn-light disabled");
      nick_usuario.className = ("form-control is-invalid");

      nickHelp.className = ("invalid-feedback")
      nickHelp.innerHTML = "Este nick ya esta ocupado, escoge otro"
    } else {
      boton_submit.className = ("btn btn-outline-success");
      nick_usuario.className = ("form-control");
      nickHelp.className = ("form-text text-muted");
      nickHelp.innerHTML = "Este nick sera el que uses para acceder a tu cuenta"
    }

  })

  email_usuario.addEventListener('keyup', e=> {

    busqueda2 = e.target.value;
    let found = emails.find((element) => element == busqueda2);
    if (found != undefined) {
      boton_submit.className = ("btn btn-light disabled");
      email_usuario.className = ("form-control is-invalid");

      emailHelp.className = ("invalid-feedback")
      emailHelp.innerHTML = "Este correo ya esta registrado, utiliza otro"
    } else {
      boton_submit.className = ("btn btn-outline-success");
      email_usuario.className = ("form-control");
      emailHelp.className = ("form-text text-muted");
      emailHelp.innerHTML = "Tranquilo, no compartiremos tu email con nadie."
    }

  })

  pass_confirmar.addEventListener('keyup', e=> {

    busqueda3 = e.target.value;

    if (busqueda3!=pass_usuario.value) {
      boton_submit.className = ("btn btn-light disabled");
      pass_confirmar.className = ("form-control is-invalid");

      passHelp.className = ("invalid-feedback")
      passHelp.innerHTML = "Las contraseñas no coinciden"
    } else {
      boton_submit.className = ("btn btn-outline-success");
      pass_confirmar.className = ("form-control");
      passHelp.className = ("form-text text-muted");
      passHelp.innerHTML = "Tranquilo, no compartiremos tu email con nadie."
    }

  })

}


async function pintar_usuarios_para_mensaje(index) {

  let ruta = ".."
  if (index) {
    ruta = "."
  }

  let urlUsuario = ruta+"/json/usuarios_activos.json";
  
  let header_conversaciones = document.getElementById('headingOne');
  let header_button = document.getElementById('headingButton');

  let nombre_usuario = document.getElementById('nombre_usuario');
  let lista_mensajes = document.getElementById('mensajes');

  let datos = await fetch (urlUsuario);
  let resultados = await datos.json();

  let i = 1;
  header_button.addEventListener('click', e=> {
    if (i%2==0){
      header_conversaciones.className = ("accordion-header btn btn-info");
    } else {
      header_conversaciones.className = ("accordion-header btn btn-primary");
    }
    i++;
  })

  nombre_usuario.addEventListener('keyup', e=> {

    busqueda = e.target.value;
    lista_mensajes.innerHTML = "";

    console.log(busqueda)

    resultados.forEach(usuario => {

      let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));

      let nombre_tipo = tipo[0]
      let nombre_clase = tipo[1]

      if (usuario.nombre.toLowerCase().includes(busqueda.toLowerCase()) || nombre_tipo.toLocaleLowerCase().includes(busqueda.toLocaleLowerCase())) {

        lista_mensajes.innerHTML += `
        <form action="${ruta}/controladores/control_mensajesLeer.php" method="GET" class="mt-3">

          <button type="submit" name="entrar" value='${usuario.id}' class="btn btn-light" style="text-transform: none; width: 100%;">
              <div class="d-flex justify-content-between">
                  <div class="d-flex flex-row align-items-center mb-2">
                      <img src="${ruta}/imagenes/foto_usuario/${usuario.foto}" alt="avatar" width="25" height="25">
                      <div class="d-flex flex-column"> 
                        <h5 class="mb-0 ms-2 text-start">${usuario.nombre}</h5>
                        <div class=" d-flex mb-0 ms-2">
                          <span class="badge ${nombre_clase}"> ${nombre_tipo} </span>
                        </div>
                      </div>
                  </div>
              </div>

              <div class="d-flex justify-content-start">
                  <small class="message-preview">¡Haz click para hablar con ${usuario.nombre}!</small>
              </div>
          </button>
        </form>
        
        `
      }

    });

    if (busqueda.length == 0) {
      lista_mensajes.innerHTML = "";
    }

  })
}

async function pintar_usuarios() {

  let nombre_usuario = document.getElementById('busqueda');
  let id_usuario = document.getElementById('busquedaID');
  let lista_usuarios = document.querySelector('tbody');
  
  let check1 = document.getElementById('optionsRadios1');
  let check2 = document.getElementById('optionsRadios2');

  let rango = document.getElementById('tipo')

  let estado=undefined

  let datos = await fetch (urlTodoslosUsuarios);
  let resultados = await datos.json();

  //Pinta el header de la tabla
  function tablaHead() {
    lista_usuarios.innerHTML = `
    <tr class="table-dark text-center">
      <th scope="col">
          id
      </th>
      <th scope="col" colspan="2">Nombre</th>
      <th scope="col">rango</th>
      <th scope="col">email</th>
      <th scope="col">nick</th>
      <th scope="col">estado</th>
      <th scope="col">Editar</th>
    </tr>
    `;
  }

  //Pinta el body de la tabla poner siempre dentro de los foreach
  function tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase){

      lista_usuarios.innerHTML += `
      <tr class="${clase_estado} text-center">
        <th scope="row">${usuario.id}</th>
        <td>
          <img src="../imagenes/foto_usuario/${usuario.foto}" alt="avatar" width="40" height="40">
        </td>
        <td>
          ${usuario.nombre}
        </td>
        <td>
          <span class="badge ${nombre_clase}"> ${nombre_tipo}</span>
        </td>
        <td>${usuario.email}</td>
        <td>${usuario.nick}</td>
        <td>
            ${nombre_estado}
        </td>
        <td>
            <a href="./control_usuario_ver.php?ver=${usuario.id}" class="fa fa-edit text-decoration-none" style="font-size:24px"></a>
        </td>
      </tr>
      `

  }

  //Por defecto
  tablaHead();
  resultados.forEach(usuario => {
    let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
    let estadoU =  estadoUsuario(parseInt(usuario.estado));

    let nombre_estado = estadoU[0]
    let clase_estado = estadoU[1]

    let nombre_tipo = tipo[0]
    let nombre_clase = tipo[1]
    tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
  })

  //Para cuando selecciona un rango
  rango.addEventListener('change', e=> {
    console.log(rango.value);
    tablaHead();
  
    resultados.forEach(usuario => {
      let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
      let estadoU =  estadoUsuario(parseInt(usuario.estado));

      let nombre_estado = estadoU[0]
      let clase_estado = estadoU[1]
  
      let nombre_tipo = tipo[0]
      let nombre_clase = tipo[1]

      if (rango.value!=-1) {

        if (usuario.tipo==rango.value) {
          tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
        }

      } else {
        tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
      }
      

    })
  })

  //Para cuando selecciona usuarios activos
  check1.addEventListener('change', function() {
    if (this.checked) {
      estado=1;
      tablaHead();

      resultados.forEach(usuario => {
        let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
        let estadoU =  estadoUsuario(parseInt(usuario.estado));
    
        let nombre_estado = estadoU[0]
        let clase_estado = estadoU[1]
    
        let nombre_tipo = tipo[0]
        let nombre_clase = tipo[1]

        if (estado==usuario.estado) {
          tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
        }

      })
    } 
  });

  //Para cuando selecciona usuarios suspendidos
  check2.addEventListener('change', function() {
    if (this.checked) {
      estado=0;
      tablaHead();

      resultados.forEach(usuario => {
        let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
        let estadoU =  estadoUsuario(parseInt(usuario.estado));
    
        let nombre_estado = estadoU[0]
        let clase_estado = estadoU[1]
    
        let nombre_tipo = tipo[0]
        let nombre_clase = tipo[1]

        if (estado==usuario.estado) {
          tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
        }

      })
    } 
  });

  //Funcion para cuando escribe en el campo ID
  id_usuario.addEventListener('keyup', e=> {
    tablaHead();
    busqueda1 = e.target.value;
    console.log(busqueda)
    resultados.forEach(usuario => {
      let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
      let estadoU =  estadoUsuario(parseInt(usuario.estado));
  
      let nombre_estado = estadoU[0]
      let clase_estado = estadoU[1]
      
      let nombre_tipo = tipo[0];
      let nombre_clase = tipo[1];

      if (usuario.id == busqueda1) {
        tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
      }

    });

    //si borra el campo ID mostrara todos los usuarios otra vez
    if (busqueda1.length == 0) {
      tablaHead();
      
      resultados.forEach(usuario => {
        let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
        let estadoU =  estadoUsuario(parseInt(usuario.estado));
  
        let nombre_estado = estadoU[0]
        let clase_estado = estadoU[1]

        let nombre_tipo = tipo[0];
        let nombre_clase = tipo[1];

        tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
      })
    }
  })

  //Funcion para cuando escribe en el campo nombre
  nombre_usuario.addEventListener('keyup', e=> {

    tablaHead();

    busqueda2 = e.target.value;
    resultados.forEach(usuario => {

      let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
      let estadoU =  estadoUsuario(parseInt(usuario.estado));
  
      let nombre_estado = estadoU[0]
      let clase_estado = estadoU[1]

      let nombre_tipo = tipo[0];
      let nombre_clase = tipo[1];

      if (rango.value==-1) {

        if (usuario.nombre.toLowerCase().includes(busqueda2.toLowerCase())) {
          tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
        }

      } else {
        if (usuario.nombre.toLowerCase().includes(busqueda2.toLowerCase()) && usuario.tipo==rango.value) {
          tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);
        }
      }
    });

    if (busqueda2.length == 0) {

      tablaHead();
      
      resultados.forEach(usuario => {
        let tipo = nombre_tipos_de_usuario(parseInt(usuario.tipo));
        let estadoU =  estadoUsuario(parseInt(usuario.estado));
  
        let nombre_estado = estadoU[0]
        let clase_estado = estadoU[1]

        let nombre_tipo = tipo[0];
        let nombre_clase = tipo[1];

        tablaBody(usuario,nombre_estado,clase_estado,nombre_tipo,nombre_clase);

      })
      
    }

  })

}


