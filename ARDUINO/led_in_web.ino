#include <LiquidCrystal.h>

#include <SoftwareSerial.h>

#define DEBUG true
#define RED_PIN 5
#define GREEN_PIN 6
#define BLUE_PIN 9

SoftwareSerial esp8266(3, 2);  // TX del ESP al pin 3, RX al pin 2

void setup() {
  pinMode(RED_PIN, OUTPUT);
  pinMode(GREEN_PIN, OUTPUT);
  pinMode(BLUE_PIN, OUTPUT);

  Serial.begin(9600);
  esp8266.begin(9600);

  sendData("AT+RST\r\n", 2000, DEBUG);
  sendData("AT+CWJAP=\"Lizok1525\",\"liza1525\"\r\n", 5000, DEBUG);
  delay(8000);  // Espera a que se conecte al Wi-Fi
  sendData("AT+CWMODE=3\r\n", 1000, DEBUG);
  delay(2000);
  sendData("AT+CIFSR\r\n", 1000, DEBUG);
  delay(2000);
  sendData("AT+CIPMUX=1\r\n", 1000, DEBUG);
  sendData("AT+CIPSERVER=1,80\r\n", 1000, DEBUG);
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

      // Estado del LED RGB
      String estado = "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n";
      estado += "<br> RGB activo";

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
        int r = random(0, 256);
        int g = random(0, 256);
        int b = random(0, 256);
     

        analogWrite(RED_PIN, r);
        analogWrite(GREEN_PIN, g);
        analogWrite(BLUE_PIN, b);
      }
      if (response.indexOf("GET /apag") > 0) {
        analogWrite(RED_PIN, 0);
        analogWrite(GREEN_PIN, 0);
        analogWrite(BLUE_PIN, 0);
      }
    }
  }

  if (debug) {
    Serial.print(response);
  }
  return response;
}
