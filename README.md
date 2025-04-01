# Mi Página Web - Currículum Personal

## Descripción

Mi página web es un currículum personal desarrollado con PHP, donde muestro mi habilidades, mis experiencia y mis contactos. Está diseñada para ser una presentación profesional en línea que facilita el acceso a mi información de manera estructurada y clara.

## Tecnologías Utilizadas

- Lenguaje: PHP

- Base de datos: MySQL

- Frontend: HTML, CSS y JavaScript

## Características

1. Presentación de mi experiencia laboral.

2. Sección de noticias en los que he salido.

3. Contacto mediante formulario seguro.

4. Protección contra ataques como SQL Injection.

## Funciones y clases 

Para mejorar la modularidad y mantenimiento del código, he implementado funciones y clases en PHP:

### Funciones en JavaScript

- **calcularCuotaMensual(C, i, n)**: Calcula la cuota mensual de un préstamo según el capital, tasa de interés y número de pagos.

- **calcularInteres(saldoPendiente, porcentajeInteres, cuotasAnuales)**: Calcula el interés de una cuota con base en el saldo pendiente y la tasa de interés.

- **calcularCapitalAmortizado(cuota, interes)**: Obtiene la cantidad del capital amortizado en una cuota.

- **cargarDatos(page)**: Recupera y muestra una lista paginada de usuarios desde una API externa.

    - Realiza una solicitud fetch a la API.

    - Procesa los datos JSON y los inserta dinámicamente en una tabla HTML.
    
    - Maneja errores en caso de problemas de conexión.



### Clases

- **Routing**: Maneja el enrutamiento de pagina y carga de controladores y vistas según la URL solicitada.

    - **Método**: handleRequest()

- **Encryption**: Maneja la encriptación de datos y la generación de hashes seguros.

     - **Métodos**: encryptSimple($texto), decryptSimple($texto), generateRandomCode($length), generarHash($password), verificarHash($password, $hash)

- **lizDb**: Gestiona la conexión y consultas a la base de datos de manera segura.

    - **Métodos**: conectar(), query($sql, $params), getRow($sql, $params = []), getResults($sql, $params = []), count($sql, $params = [])

- **Core**: Contiene utilidades para manipulación de texto y conversión de contenido.

    - **Métodos**: convertirTextoAHTML($texto), obtenerArticuloHTML($id, $conn),generarSlug($titulo), print_arr($arr)


## Seguridad

Dado mi conocimiento en seguridad informática y bases de datos, he implementado buenas prácticas de seguridad, como:

- Uso de consultas preparadas para evitar SQL Injection.

- Validación y saneamiento de datos de entrada.

- Gestión de sesiones segura para autenticación de usuarios.

## Objetivo

Este proyecto no solo es una presentación profesional, sino también una demostración de mis habilidades en **desarrollo web y seguridad en bases de datos**.

*Para más información, puedes visitar la página directamente*: liza.ma-no.es