<?php
?>
<div class="row intro">
    <h1> Test</h1>
    <nav aria-label="page navigation example">
        <button class="pagination-btn" data-page="-1">Previous</button>
        <button class="pagination-btn" data-page="1">1</button>
        <button class="pagination-btn" data-page="2">2</button>
        <button class="pagination-btn" data-page="3">3</button>
        <button class="pagination-btn" data-page="4">4</button>
        <button class="pagination-btn" data-page="5">5</button>
        <button class="pagination-btn" data-page="6">6</button>
        <button class="pagination-btn" data-page="7">7</button>
        <button class="pagination-btn" data-page="8">8</button>
        <button class="pagination-btn" data-page="9">9</button>
        <button class="pagination-btn" data-page="10">10</button>
        <button class="pagination-btn" data-page="11">11</button>
        <button class="pagination-btn" data-page="12">12</button>
        <button class="pagination-btn" data-page="13">13</button>
        <button class="pagination-btn" data-page="14">14</button>
        <button class="pagination-btn" data-page="15">15</button>
        <button class="pagination-btn" data-page="16">Next</button>
    </nav>
    <div id="resultado" class="col"></div>
</div>
<script>
    cargarDatos();


    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".pagination-btn");
        const container = document.getElementById("resultado");
        let page = 1;

        buttons.forEach(button => {
            button.addEventListener("click", function() {
                page = parseInt(this.getAttribute("data-page")); // Convertir el valor de data-page a número


                if (page === -1) { // Si es el botón "Previous", restar 1
                    page = page - 1;
                } else if (page === 10) { // Si es el botón "Next", sumar 1
                    page = page + 1;
                }

                // Asegurarse de que la página no sea menor que 1
                if (page < 1) page = 1;

                console.log(page);
                //currentPage = page;
                cargarDatos(page);



            });
        });

        /*
        function loadData(page) {
            container.innerHTML = "";
            fetch("https://liza.mano.app/api/usuarios") // Carga el archivo JSON
                .then(response => response.json())
                .then(data => {
                    // Suponiendo que el JSON es un array de objetos
                    const itemsPerPage = 20; // Número de elementos por página
                    const startIndex = (page - 1) * itemsPerPage;
                    const endIndex = startIndex + itemsPerPage;
                    const paginatedData = data.slice(startIndex, endIndex);

                    //--------------------------
                    const tabla = document.createElement("table")

                    const encabezado = document.createElement("tr");
                    encabezado.innerHTML = '<th class="p-2">ID</th><th class="p-2">Nombre</th><th class="p-2">Email</th>';
                    tabla.appendChild(encabezado);

                    paginatedData.forEach(usuario => {
                        const fila = document.createElement("tr");
                        fila.innerHTML = `
                        <td class="p-2">${usuario.ID}</td> 
                        <td class="p-2">${usuario.name}</td>
                        <td class="p-2"> ${usuario.email}</td>`;

                        tabla.appendChild(fila);
                    })
                    container.appendChild(tabla);
                })
                .catch(error => console.error("Error cargando los datos:", error));
        }

        // Cargar la primera página al inicio
        loadData();*/
    });

    async function cargarDatos(page = 1) {
        try {

            document.getElementById("resultado").innerHTML = "";

            var pageRes = (page - 1) * 25;

            console.log(pageRes);

            const respuesta = await fetch("https://liza.mano.app/api/usuarios?fr=" + pageRes + "&st=25"); // URL del JSON remoto
            const datos = await respuesta.json(); // Convertir a JSON

            const lista = document.getElementById("resultado");

            const tabla = document.createElement("table")

            const encabezado = document.createElement("tr");
            encabezado.innerHTML = '<th class="p-2">ID</th><th class="p-2">Nombre</th><th class="p-2">Email</th>';
            tabla.appendChild(encabezado);

            // Acceder a la propiedad "content" que contiene los usuarios
            datos.content.forEach(usuario => {
                const fila = document.createElement("tr");
                fila.innerHTML = `<td class="p-2">${usuario.ID}</td> <td class="p-2">${usuario.name}</td><td class="p-2"> ${usuario.email}</td>`;
                tabla.appendChild(fila);
            });
            resultado.appendChild(tabla);
        } catch (error) {
            console.error("Error al cargar el JSON:", error);
        }
    }


    //alert("funciona")
</script>