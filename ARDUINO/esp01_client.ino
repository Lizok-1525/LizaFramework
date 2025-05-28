#include <SoftwareSerial.h>

String host = "192.168.1.136";
String ruta = "/miapi.php";

#define DEBUG true
#define LED 5

SoftwareSerial esp8266(10, 11);  // RX, TX

void setup() {
  pinMode(LED, OUTPUT);

  Serial.begin(9600);
  esp8266.begin(9600);

  // Reiniciar el ESP
  sendData("AT+RST\r\n", 2000, DEBUG);
  delay(2000);  // Espera tras reiniciar

  // Establecer modo estación + AP (modo 3)
  sendData("AT+CWMODE=3\r\n", 1000, DEBUG);
  delay(1000);

  // Conectar a red Wi-Fi (usa red de 2.4 GHz, no 5G)
  sendData("AT+CWJAP=\"MIWIFI_QDGY_2G\",\"xGST6s7C\"\r\n", 8000, DEBUG);
  delay(8000);  // Esperar conexión

  // Obtener IP del ESP
  sendData("AT+CIFSR\r\n", 2000, DEBUG);
  delay(1000);

  // Habilitar múltiples conexiones
  sendData("AT+CIPMUX=0\r\n", 1000, DEBUG);
  delay(1000);

  // Iniciar servidor web en el puerto 80
  // sendData("AT+CIPSERVER=1,80\r\n", 1000, DEBUG);
  // delay(1000);
  obtenerDatosDesdeLocalhost();
}



void loop() {

  if (esp8266.available()) {
    if (esp8266.find("+IPD,")) {
      delay(500);


      int connectionId = esp8266.read() - '0';  // Convierte el caracter a número


      // Página web con botones
      String webpage = "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n";
      webpage += "<h1>Yelyzaveta Krasnolutska</h1>";
      webpage += "<form method=\"get\" action=\"/enci\">";
      webpage += "<button type=\"submit\">ON - ENCIENDE</button></form>";
      webpage += "<form method=\"get\" action=\"/apag\">";
      webpage += "<button type=\"submit\">OFF - APAGA</button></form>";


      String cipSend = "AT+CIPSEND=" + String(connectionId) + "," + String(webpage.length()) + "\r\n";
      sendData(cipSend, 500, DEBUG);
      sendData(webpage, 500, DEBUG);
      // Mensaje de estado
      String estado = "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n";
      estado += (digitalRead(LED) == HIGH) ? "<br> ON - Encendido" : "<br> OFF - Apagado";
      cipSend = "AT+CIPSEND=" + String(connectionId) + "," + String(estado.length()) + "\r\n";
      sendData(cipSend, 500, DEBUG);
      sendData(estado, 500, DEBUG);
      // Cierra la conexión
      sendData("AT+CIPCLOSE=" + String(connectionId) + "\r\n", 500, DEBUG);
    }
  }
}


// Función para enviar datos al ESP y comprobar respuesta
String sendData(String command, const int timeout, boolean debug) {
  String response = "";
  esp8266.print(command);

  long int time = millis();
  while ((millis() - time) < timeout) {
    while (esp8266.available()) {
      char c = esp8266.read();
      response += c;
    }
  }
  if (debug) {
    Serial.print(response);
  }
  return response;
}

void obtenerDatosDesdeLocalhost() {
  sendData("AT+CIPSTART=\"TCP\",\"" + host + "\",80\r\n", 5000, DEBUG);
  delay(3000);

  String httpGet = "GET " + ruta + " HTTP/1.1\r\nHost: " + host + "\r\nConnection: close\r\n\r\n";

  // Paso 1: Enviar petición
  sendData("AT+CIPSEND=" + String(httpGet.length()) + "\r\n", 2000, DEBUG);
  delay(100);  // pequeña espera para el prompt '>'
  esp8266.print(httpGet);  // enviamos sin leer respuesta aún

  // Paso 2: Leer respuesta completa (encabezado + JSON)
  Serial.println("Esperando datos...");
  String response = "";
  long t0 = millis();
  while (millis() - t0 < 10000) {
    while (esp8266.available()) {
      char c = esp8266.read();
      response += c;
      t0 = millis();  // reinicia timeout
    }
  }

  // Paso 3: Mostrar todo
  Serial.println("📦 Respuesta completa:");
  Serial.println(response);

  // Buscar estado HTTP
  if (response.indexOf("HTTP/1.1 200") >= 0) {
    Serial.println("✅ Respuesta 200 OK");
  } else if (response.indexOf("HTTP/1.1 404") >= 0) {
    Serial.println("❌ Error 404 - Página no encontrada");
  } else {
    Serial.println("⚠️ Otro código de respuesta HTTP:");
    int pos = response.indexOf("HTTP/1.1 ");
    if (pos >= 0) {
      Serial.println(response.substring(pos, pos + 30));
    }
  }

  // Paso 4: Extraer JSON
  int jsonStart = response.indexOf('{');
  if (jsonStart == -1) jsonStart = response.indexOf('[');

  if (jsonStart >= 0) {
    String jsonData = response.substring(jsonStart);
    Serial.println("🟢 JSON extraído:");
    Serial.println(jsonData);

    if (jsonData.indexOf("hola") >= 0) {
      digitalWrite(LED, HIGH);
    } else {
      digitalWrite(LED, LOW);
    }
  } else {
    Serial.println("⚠️ No se encontró JSON.");
  }
}
