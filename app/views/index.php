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
      </div>
    </div>
  </div>
  <?php include(BASE_PATH . "/template/standard/navegacion.inc.php"); ?>
</div>



<script>
  let ultimaFrase = "";

  function cargarScript(url, callback) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = url;

    // Agregar el evento load para ejecutar la función de callback
    script.onload = function() {
      callback();
    };

    document.head.appendChild(script);
  }

  function miFuncion() {
    console.log("El script externo ha sido cargado y esta función se ha ejecutado.");
    speechSynthesis.getVoices().forEach(voice => console.log(voice.name, voice.lang));

  }

  // Esperar a que el DOM esté completamente cargado
  document.addEventListener("DOMContentLoaded", function() {
    // Cargar el script externo y ejecutar miFuncion después de que se haya cargado
    cargarScript("https://cdn.jsdelivr.net/npm/artyom.js@1.0.6/build/artyom.window.min.js", miFuncion);


  });

  document.getElementById("btn").addEventListener("click", function() {
    navigator.mediaDevices.getUserMedia({
      audio: true
    })
    executeAtenea();
  }, false);

  function executeAtenea() {
    var artyom = new Artyom();
    artyom.say("Hola, soy Alan, tu asistente virtual. ¿En qué puedo ayudarte hoy?");
    artyom.fatality(); // use this to stop any of
    setTimeout(function() { // if you use artyom.fatality , wait 250 ms to initialize again.
      artyom.initialize({
        lang: "es-ES", // A lot of languages are supported. Read the docs !
        continuous: true, // Artyom will listen forever
        listen: true, // Start recognizing
        debug: true, // Show everything in the console
        speed: 1 // talk normally
      }).then(() => {
        // Aquí añadimos los comandos justo después de iniciar Artyom
        artyom.addCommands([{
            indexes: ["hola", "buenos días"],
            action: function() {
              artyom.fatality(); // parar la escucha antes de hablar
              ultimaFrase = "¡Hola! ¿Cómo estás?";
              artyom.say(ultimaFrase, {
                onEnd: function() {
                  // Volver a inicializar para escuchar de nuevo
                  artyom.initialize({
                    lang: "es-ES",
                    continuous: true,
                    listen: true,
                    debug: true,
                    speed: 1
                  });
                }
              });
            }
          },
          /* {
             indexes: ["Привет", "_blank"],
             action: function() {
               artyom.fatality(); // parar la escucha antes de hablar
               artyom.say("Привет, как ви?", {
                 onEnd: function() {
                   // Volver a inicializar para escuchar de nuevo
                   artyom.initialize({
                     lang: "es-ES",
                     continuous: true,
                     listen: true,
                     debug: true,
                     speed: 1
                   });
                 }
               });
             }
           },*/
          {
            indexes: ["abre YouTube"],
            action: function() {
              window.open("https://www.youtube.com", "_blank");
            }
          },
          {
            indexes: ["todo sobre *"],
            smart: true, // muy importante para que el wildcard funcione
            action: function(i, wildcard) {
              artyom.fatality();
              ultimaFrase = `Aqui esta informacion sobre ${wildcard}`; // parar la escucha antes de hablar
              artyom.say(ultimaFrase, {
                onEnd: function() {
                  // Volver a inicializar para escuchar de nuevo
                  artyom.initialize({
                    lang: "es-ES",
                    continuous: true,
                    listen: true,
                    debug: true,
                    speed: 1
                  });
                }
              });
              window.open("https://liza.ma-no.es/sobre_mi", "_blank");
            }
          },
          {
            indexes: ["cómo contactar con *",
              "como puedo contactar con *",
              "quiero contactar con *",
              "necesito contactar con *", "contactar con *",
            ],
            smart: true, // muy importante para que el wildcard funcione
            action: function(i, wildcard) {
              artyom.fatality();
              ultimaFrase = `Para contactar con ${wildcard}, puedes rellenar este formulario.`; // parar la escucha antes de hablar
              artyom.say(ultimaFrase, {
                onEnd: function() {
                  // Volver a inicializar para escuchar de nuevo
                  artyom.initialize({
                    lang: "es-ES",
                    continuous: true,
                    listen: true,
                    debug: true,
                    speed: 1
                  });
                }
              });
              window.open("https://liza.ma-no.es/contacto", "_blank");
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
          },
          {
            indexes: ["cuéntame un chiste"],
            action: function() {
              artyom.say("¿Por qué el café fue al médico? Porque se sentía expreso.");
            }
          },
          {
            indexes: ["noticias", "donde hay noticias en esta pagina"],
            action: function() {
              artyom.fatality();
              ultimaFrase = `Las noticias de esta pagina`; // parar la escucha antes de hablar
              artyom.say(ultimaFrase, {
                onEnd: function() {
                  // Volver a inicializar para escuchar de nuevo
                  artyom.initialize({
                    lang: "es-ES",
                    continuous: true,
                    listen: true,
                    debug: true,
                    speed: 1
                  });
                }
              });
              window.open("https://liza.ma-no.es/noticias", "_blank");
            }
          }
        ]);
      });
    }, 3000);

    console.log("%cAtenea started...", "font: 2em sans-serif; color: blue; background-color: white;");

  }
</script>