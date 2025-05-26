
#include <SoftwareSerial.h>


#define DEBUG true
#define LED 5


SoftwareSerial esp8266(3, 2); // TX del ESP al pin 3, RX al pin 2


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
  String ipInfo = sendData("AT+CIFSR\r\n", 2000, DEBUG);
  delay(1000);

  // Extraer y mostrar IP en el monitor serial
  int ipIndex = ipInfo.indexOf("+CIFSR:STAIP,\"");
  if (ipIndex != -1) {
    int start = ipIndex + 15;
    int end = ipInfo.indexOf("\"", start);
    String ip = ipInfo.substring(start, end);
    Serial.println("✅ IP del ESP8266: " + ip);
  } else {
    Serial.println("⚠️ No se pudo obtener la IP.");
  }

  // Habilitar múltiples conexiones
  sendData("AT+CIPMUX=1\r\n", 1000, DEBUG);
  delay(1000);

  // Iniciar servidor web en el puerto 80
  sendData("AT+CIPSERVER=1,80\r\n", 1000, DEBUG);
  delay(1000);
}



void loop() {
  if (esp8266.available()) {
    if (esp8266.find("+IPD,")) {
      delay(500);


      int connectionId = esp8266.read() - '0'; // Convierte el caracter a número


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
  while ((time + timeout) > millis()) {
    while (esp8266.available()) {
      char c = esp8266.read();
      response += c;
      // Encender o apagar LED
      if (response.indexOf("GET /enci") > 0) {
        digitalWrite(LED, HIGH);
      }
      if (response.indexOf("GET /apag") > 0) {
        digitalWrite(LED, LOW);
      }
    }
  }
  if (debug) {
    Serial.print(response);
  }
  return response;
}
