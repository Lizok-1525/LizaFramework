<div class="container ">
  <div class="row ">
    <div class="col-sm-4 text-center p-1">
      <div class="custom-bg-5">
      </div>
    </div>
    <div class="col-sm-8 p-5 intro bg-light text-dark">
      <h2>Yelyzaveta Krasnolutska</h2>
      <h3><i>Periodista</i></h3>
      <p>Apasionada de la comunicación y la narración de historias, con un fuerte deseo de convertirme en periodista. Mi curiosidad innata y mi compromiso con la verdad me impulsan a investigar y explorar temas de relevancia social. A través de mi formación académica y experiencias prácticas, he desarrollado habilidades en redacción, investigación y análisis crítico, que me permiten abordar diversas temáticas con profundidad y objetividad. </p>
      <p>Además, cuento con conocimientos en programación web, lo que me permite crear y gestionar contenido digital de manera efectiva. Estoy emocionada por la oportunidad de contribuir al mundo del periodismo, informando y conectando a las personas con las historias que importan.</p>
      <?php
      ?>
      <div>
        <button class="btn btn-outline-secondary btn-lg" id="btn">Activar interfaz de voz</button>

        <p class="text-muted m-2 fst-italic">Haz clic en el botón y di "Alan" para activar la interfaz de voz y comenzar a interactuar con Alan.</p>
      </div>
      <div id="div1"></div>
      <select class="form-select text-bg-secondary">
        <option selected>Opciones de comandos para Alan</option>
        <option value="1">abre YouTube</option>
        <option value="2">todo sobre Liza o Elizabeth</option>
        <option value="3">contactar con Liza o Elizabeth</option>
        <option value="4">repite lo que has dicho</option>
        <option value="5">cuéntame un chiste</option>
        <option value="6">noticias</option>
      </select>
    </div>
  </div>
  <?php include(BASE_PATH . "/template/standard/navegacion.inc.php"); ?>
</div>



<script>
  let ultimaFrase = "";
  let artyom = null;
  let escuchandoComandos = false;
  let tiempoEscucha = 10000; // tiempo en ms que escucha después de decir "Alan"

  function cargarScript(url, callback) {
    const script = document.createElement("script");
    script.type = "text/javascript";
    script.src = url;
    script.onload = callback;
    document.head.appendChild(script);
  }

  function reiniciarArtyom() {
    artyom.initialize({
      lang: "es-ES",
      continuous: true,
      listen: true,
      debug: true,
      speed: 1
    });
  }

  function activarEscuchaTemporal() {
    escuchandoComandos = true;
    console.log("✅ Modo escucha ACTIVADO");
    setTimeout(() => {
      escuchandoComandos = false;
      console.log("❌ Modo escucha DESACTIVADO");
    }, tiempoEscucha);
  }

  function responder(frase) {
    artyom.fatality();
    ultimaFrase = frase;
    artyom.say(frase, {
      onEnd: reiniciarArtyom
    });
  }

  function agregarComandosBasicos() {
    artyom.addCommands([{
        indexes: ["alan", "ala", "oye alan", "hola alan"],
        action: function() {
          responder("Hola, dime.");
          activarEscuchaTemporal();
        }
      },
      {
        indexes: ["repite", "puedes repetir", "qué dijiste"],
        action: function() {
          if (ultimaFrase) {
            artyom.say(ultimaFrase);
          } else {
            artyom.say("Todavía no he dicho nada.");
          }
        }
      }, {
        indexes: ["bien", "muy bien", "super"],
        action: function() {
          responder("Me alegro. ¿En que puedo ayudarte?");
        }
      }
    ]);
  }

  function agregarComandosAvanzados() {
    artyom.addCommands([{
        indexes: ["hola", "buenos días"],
        action: function() {
          if (!escuchandoComandos) return;
          responder("¡Hola! ¿Cómo estás?");
        }
      },
      {
        indexes: ["cómo te encuentras", "qué tal", "cómo estás", "como estas"],
        action: function() {
          if (!escuchandoComandos) return;
          responder("Estoy muy bien, gracias por preguntar. ¿Y tú?");
        }
      },
      {
        indexes: ["abre YouTube"],
        action: function() {
          if (!escuchandoComandos) return;
          responder("Abriendo YouTube");
          window.open("https://www.youtube.com", "_blank");
        }
      },
      {
        indexes: ["enciende luz", "enciende la luz", "enciende rapido la luz"],
        action: function() {
          if (!escuchandoComandos) return;
          responder("Encendiendo la luz ahora.");

          $.ajax({
            url: "http://192.168.1.136/actualizar_orden.php?estado=1",
            method: "GET",
            success: function() {
              console.log("Orden de encender enviada");
            },
            error: function() {
              console.log("Error al enviar orden");
            }
          });
        }
      },
      {
        indexes: ["apaga luz", "apaga la luz", "apaga rapido la luz"],
        action: function() {
          if (!escuchandoComandos) return;
          responder("Luz apagada.");

          $.ajax({
            url: "http://192.168.1.136/actualizar_orden.php?estado=0",
            method: "GET",
            success: function() {
              console.log("Orden de encender enviada");
            },
            error: function() {
              console.log("Error al enviar orden");
            }
          });
        }
      },
      {
        indexes: ["cuéntame un chiste"],
        action: function() {
          if (!escuchandoComandos) return;
          responder("¿Por qué el café fue al médico? Porque se sentía expreso.");
        }
      },
      {
        indexes: ["todo sobre *"],
        smart: true,
        action: function(i, wildcard) {
          if (!escuchandoComandos) return;
          responder(`Aquí está la información sobre ${wildcard}`);
          window.open("https://liza.ma-no.es/sobre_mi", "_blank");
        }
      },
      {
        indexes: ["cómo contactar con *", "quiero contactar con *", "necesito contactar con *", "contactar con *"],
        smart: true,
        action: function(i, wildcard) {
          if (!escuchandoComandos) return;
          responder(`Para contactar con ${wildcard}, puedes rellenar este formulario.`);
          window.open("https://liza.ma-no.es/contacto", "_blank");
        }
      }, {
        indexes: ["alan acaba trabajo", "alan termina trabajo", "alan desconéctate"],
        action: function() {
          responder("Entendido. Cerrando sesión. Hasta pronto.");
          artyom.fatality();
          escuchandoComandos = false;
        }
      },
      {
        indexes: ["noticias", "dónde hay noticias en esta página"],
        action: function() {
          if (!escuchandoComandos) return;
          responder("Las noticias están disponibles en la sección de noticias.");
          window.open("https://liza.ma-no.es/noticias", "_blank");
        }
      }
    ]);
  }

  function executeAlan() {
    if (!artyom) artyom = new Artyom();
    artyom.fatality();

    setTimeout(() => {
      reiniciarArtyom();
      agregarComandosBasicos();
      agregarComandosAvanzados();
    }, 1000);

    console.log("%cAlan esta listo. Di 'Alan' para activarlo.", "font: 1.5em sans-serif; color: green;");
  }

  // Espera a que cargue el DOM y el script externo
  document.addEventListener("DOMContentLoaded", () => {
    cargarScript("https://cdn.jsdelivr.net/npm/artyom.js@1.0.6/build/artyom.window.min.js", () => {
      console.log("✅ Artyom.js cargado");
      // Botón opcional para iniciar con permiso de micrófono
      document.getElementById("btn").addEventListener("click", () => {
        navigator.mediaDevices.getUserMedia({
          audio: true
        }).then(() => {
          executeAlan();
        });
      });
    });
  });
</script>