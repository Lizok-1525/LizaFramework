#include <SoftwareSerial.h>

#define DEBUG true

SoftwareSerial esp8266(3, 2); // RX del Arduino en 3, TX en 2

void setup() {
  Serial.begin(9600);
  esp8266.begin(9600);

  // Reiniciar el ESP
  sendData("AT+RST\r\n", 2000, DEBUG);

  // Modo estación (cliente)
  sendData("AT+CWMODE=1\r\n", 1000, DEBUG);

  // Conexión a Wi-Fi
  sendData("AT+CWJAP=\"MIWIFI_QDGY_2G\",\"xGST6s7C\"\r\n", 8000, DEBUG);

  // Iniciar conexión TCP con el servidor
  // Puedes cambiar "example.com" por una IP o dominio local
  sendData("AT+CIPSTART=\"TCP\",\"example.com\",80\r\n", 3000, DEBUG);

  // Enviar petición HTTP GET
  String httpGet = "GET / HTTP/1.1\r\nHost:example.com\r\nConnection: close\r\n\r\n";
  String cmd = "AT+CIPSEND=" + String(httpGet.length()) + "\r\n";
  sendData(cmd, 1000, DEBUG);

  sendData(httpGet, 3000, DEBUG);
}

void loop() {
  // No hace nada en loop, solo envía la petición una vez
}

// Función para enviar comandos AT y recibir respuesta
String sendData(String command, const int timeout, boolean debug) {
  String response = "";
  esp8266.print(command); // enviar comando AT
  long int time = millis();
  while ((time + timeout) > millis()) {
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
