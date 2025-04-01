<?php
?>
<style>

</style>
<div class="row intro">

    <div class="center ">
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


        // ---------------------------------------------------------
        function calcularCuotaMensual(C, i, n) {
            return C * ((Math.pow(1 + i, n) * i) / (Math.pow(1 + i, n) - 1));
        }

        function calcularInteres(saldoPendiente, porcentajeInteres, cuotasAnuales) {
            return (saldoPendiente * (porcentajeInteres / 100)) / cuotasAnuales;
        }

        function calcularCapitalAmortizado(cuota, interes) {
            return cuota - interes;
        }

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