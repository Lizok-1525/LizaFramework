<?php
?>
<style>

</style>
<div class="row intro">

    <div class="center ">
        <!--     <form name="forms">
            <input type="text" id="display" name="display" class="mb-3 w-20 fs-5 px-2 py-2" disabled />
            <div class="buttons">
                <div class="btn-group btn-group-lg me-2" role="group" aria-label="Second group">
                    <button type="button" id="btn" class="btn btn-secondary" value="7">7</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="8">8</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="9">9</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="/">/</button>
                </div><br />
                <div class="btn-group btn-group-lg me-2" role="group" aria-label="Second group">
                    <button type="button" id="btn" class="btn btn-secondary" value="4">4</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="5">5</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="6">6</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="*">*</button>
                </div><br />
                <div class="btn-group btn-group-lg me-2" role="group" aria-label="Second group">
                    <button type="button" id="btn" class="btn btn-secondary" value="1">1</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="2">2</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="3">3</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="-">-</button>
                </div><br />
                <div class="btn-group btn-group-lg me-2" role="group" aria-label="Second group">
                    <button type="button" id="btn" class="btn btn-secondary" value=".">.</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="0">0</button>
                    <button type="button" id="btn" class="btn btn-secondary" value="+">+</button>
                    <button type="button" id="clear" class="btn btn-secondary" value="C" onclick="document.forms.display.value=''">C</button>
                </div><br />
                <div class="btn-group btn-group-lg me-2" role="group" aria-label="Second group">
                    <button type="button" id="equal" class="btn btn-secondary" value="=" onclick="document.forms.display.value=eval(document.forms.display.value)">=</button>
                </div>
        </form> -->
        <div class="col-sm-5 p-3 calculator">
            <form name="forms">
                <input type="text" id="display" name="display" class="form-control mb-3" />
                <div class="buttons">
                    <div class="row">
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="7">7</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="8">8</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="9">9</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-warning" value="/">/</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="4">4</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="5">5</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="6">6</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-warning" value="*">*</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="1">1</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="2">2</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="3">3</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-warning" value="-">-</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value=".">.</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-light border border-secondary" value="0">0</button>
                        </div>
                        <div class="col">
                            <button type="button" id="btn" class="btn btn-warning" value="+">+</button>
                        </div>
                        <div class="col">
                            <button type="button" id="clear" class="btn btn-danger" value="C" onclick="document.forms.display.value=''">C</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="button" id="equal" class="btn btn-success" value="=" onclick="document.forms.display.value=eval(document.forms.display.value)">=</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-5 mt-3">
            <h2>Calculadora de hipoteca</h2>
            <div class="form-group ">
                <label for="capital">Capital Inicial (€):</label>
                <input type="number" class="form-control" id="capital" value="276000">
            </div>

            <div class="form-group ">
                <label for="interes">Tipo de Interés (%):</label>
                <input type="number" class="form-control" id="interes" value="3.91">

            </div>

            <div class="form-group ">
                <label for="plazo">Plazo de Amortización (Años):</label>
                <select class="form-control" id="plazo">
                    <option value="25">40</option>
                    <option value="30" selected>30</option>
                    <option value="25">25</option>
                    <option value="20">20</option>
                    <option value="15">15</option>
                    <option value="10">10</option>
                    <option value="5">5</option>
                </select>
            </div>

            <button class="btn btn-primary" id="calcular">Calcular</button>

            <div id="resultado" class="mt-4"></div>
        </div>

    </div>
    <script>
        const buttons = document.querySelectorAll("#btn");
        const display = document.getElementById("display");
        buttons.forEach((button) => {
            button.addEventListener("click", () => {
                display.value += button.value;

            });
        });

        // --------------------------------------------------------------------------
        /*
        document.addEventListener('DOMContentLoaded', function() {
            const capitalInput = document.getElementById('capital');
            const interesInput = document.getElementById('interes');
            const plazoSelect = document.getElementById('plazo');
            const calcularBtn = document.getElementById('calcular');
            const resultadoDiv = document.getElementById('resultado');

            const capitalSlider = document.getElementById('capitalSlider');
            const interesSlider = document.getElementById('interesSlider');

            // Sincronizar inputs numéricos y sliders
            capitalSlider.addEventListener('input', function() {
                capitalInput.value = this.value;
            });

            capitalInput.addEventListener('input', function() {
                capitalSlider.value = this.value;
            });

            interesSlider.addEventListener('input', function() {
                interesInput.value = this.value;
            });

            interesInput.addEventListener('input', function() {
                interesSlider.value = this.value;
            });

            calcularBtn.addEventListener('click', function() {
                const capital = parseFloat(capitalInput.value);
                const interes = parseFloat(interesInput.value) / 100 / 12; // Interés mensual
                const plazo = parseInt(plazoSelect.value) * 12; // Plazo en meses

                const cuotaMensual = (capital * interes * Math.pow(1 + interes, plazo)) / (Math.pow(1 + interes, plazo) - 1);

                if (isNaN(cuotaMensual)) {
                    resultadoDiv.innerHTML = '<p class="text-danger">Por favor, ingresa valores válidos.</p>';
                } else {
                    resultadoDiv.innerHTML = `<p>Cuota mensual: ${cuotaMensual.toFixed(2)} €</p>`;
                }
            });
        }); */

        // --------------------------------------------------------------------------


        function calcularCuotaMensual(C, i, n) {
            return C * ((Math.pow(1 + i, n) * i) / (Math.pow(1 + i, n) - 1));
        }


        function calcularInteres(saldoPendiente, porcentajeInteres, cuotasAnuales) {
            return (saldoPendiente * (porcentajeInteres / 100)) / cuotasAnuales;
        }

        function calcularCapitalAmortizado(cuota, interes) {
            return cuota - interes;
        }
        /*
                function calcularAmortizacioTotal(capital, amortizacion) {
                    return capital + amortizacion;
                }

                function calcularCapitalPendiente(importeFinanciado, amortizacion) {
                    return capital + amortizacion;
                }*/

        //let calcularBtn = document.getElementById('calcular');
        //let resultado = document.getElementById('resultado');

        document.getElementById('calcular').addEventListener('click', function() {
            // Obtener valores de los inputs y convertirlos a números
            let importeFinanciado = parseFloat(document.getElementById('capital').value);
            let porcentajeInteres = parseFloat(document.getElementById('interes').value);
            let años = parseInt(document.getElementById('plazo').value);
            let cuotasAnuales = 12; // Pagos al año

            // Validar que los valores sean correctos
            if (isNaN(importeFinanciado) || isNaN(porcentajeInteres) || isNaN(años) || importeFinanciado <= 0 || porcentajeInteres < 0 || años <= 0) {
                resultadoDiv.innerHTML = "<p style='color:red;'>Por favor, ingrese valores válidos.</p>";
                return;
            }

            // Cálculos
            let tasaMensual = (porcentajeInteres / 100) / cuotasAnuales;
            let totalPagos = años * cuotasAnuales;
            let cuotaMensual = calcularCuotaMensual(importeFinanciado, tasaMensual, totalPagos);

            /*let interesMensual = calcularInteres(importeFinanciado, porcentajeInteres, cuotasAnuales);
            let capitalAmortizado = calcularCapitalAmortizado(cuotaMensual, interesMensual);
*/
            let saldoPendiente = importeFinanciado;
            let amortizacionAcumulada = 0;

            let tablaHTML = `
        <table border="1" class="table table-bordered">
            <tr>
                <th>Cuota #</th>
                <th>Cuota mensual</th>
                <th>Interés</th>
                <th>Capital amortizado</th>
                <th>Amortización acumulada</th>
                <th>Capital pendiente</th>
            </tr>
    `;

            /*let interesMensual = calcularInteres(importeFinanciado, porcentajeInteres, cuotasAnuales);
            let capitalAmortizado = calcularCapitalAmortizado(cuotaMensual, interesMensual);
*/




            for (let cuotaNum = 1; cuotaNum <= totalPagos; cuotaNum++) {
                let interesMensual = calcularInteres(saldoPendiente, porcentajeInteres, cuotasAnuales); // Mantener fijo
                capitalAmortizado = calcularCapitalAmortizado(cuotaMensual, interesMensual);

                if (saldoPendiente < capitalAmortizado) {
                    capitalAmortizado = saldoPendiente;
                }

                amortizacionAcumulada += capitalAmortizado;
                saldoPendiente -= capitalAmortizado;
                saldoPendiente = saldoPendiente < 0 ? 0 : saldoPendiente;

                tablaHTML += `
            <tr>
                <td>${cuotaNum}</td>
                <td>${cuotaMensual.toFixed(2)} €</td>
                <td>${interesMensual.toFixed(2)} €</td>
                <td>${capitalAmortizado.toFixed(2)} €</td>
                <td>${amortizacionAcumulada.toFixed(2)} €</td>
                <td>${saldoPendiente.toFixed(2)} €</td>
            </tr>
        `;
            }

            tablaHTML += `</table>`;
            document.getElementById('resultado').innerHTML = tablaHTML;
        });
        /*
        let importeFinanciado = document.getElementById('capital');
        let porcentajeInteres = document.getElementById('interes');
        let cuotasAnuales = 12;
        let años = document.getElementById('plazo');


        importeFinanciado.addEventListener('input', function() {
            importeFinanciado.value = this.value;
        });


        porcentajeInteres.addEventListener('input', function() {
            porcentajeInteres.value = this.value;
        });

        let tasaMensual = (porcentajeInteres / 100) / cuotasAnuales;
        let totalPagos = años * cuotasAnuales;

        let cuotaMensual = calcularCuotaMensual(importeFinanciado, tasaMensual, totalPagos);
        let interesMensual = calcularInteres(importeFinanciado, porcentajeInteres, cuotasAnuales);
        let capitalAmortizado = calcularCapitalAmortizado(cuotaMensual, interesMensual);

        // Mostrar resultados
        console.log("Cuota mensual:", cuotaMensual.toFixed(2));
        console.log("Interés mensual:", interesMensual.toFixed(2));
        console.log("Capital amortizado:", capitalAmortizado.toFixed(2));

       */


        // Ejemplo de uso:
        /*   let importeFinanciado = 10000; // Monto financiado
           let porcentajeInteres = 5; // Interés anual en porcentaje (ejemplo: 5%)
           let cuotasAnuales = 12; // Número de cuotas por año

           let interes = calcularInteres(importeFinanciado, porcentajeInteres, cuotasAnuales);
           console.log("Interés por cuota:", interes.toFixed(2));*/
        /*let C = 150000; // Capital prestado
               let i = 0.0025; // Tasa de interés mensual (ejemplo: 5%)
               let n = 360; // Número de meses

               let cuota = calcularCuotaMensual(C, i, n);
               console.log("Cuota mensual:", cuota.toFixed(2));*/

        //alert("funciona")
    </script>