#include <Wire.h> 
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <Keypad.h>
#include <qrcode.h>

// Definición de la pantalla OLED
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET -1
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

// Longitud máxima de la contraseña
#define Password_Length 8 

// Pin para la señal de salida (ej. LED, relé, etc.)
int signalPin = 12;

// Variables para contraseña
char Data[Password_Length]; 
char Master[Password_Length] = "1234561"; 
byte data_count = 0;
bool Pass_is_good;
char customKey;

// Configuración del teclado matricial 4x4
const byte ROWS = 4;
const byte COLS = 4;
char hexaKeys[ROWS][COLS] = {
  {'1', '2', '3', 'A'},
  {'4', '5', '6', 'B'},
  {'7', '8', '9', 'C'},
  {'*', '0', '#', 'D'}
};

// Pines conectados a filas y columnas del teclado
byte rowPins[ROWS] = {9, 8, 7, 6};
byte colPins[COLS] = {5, 4, 3, 2};

// Inicialización del teclado
Keypad customKeypad = Keypad(makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS);

// URL que se mostrará como QR si la contraseña es correcta
const char *url = "https://liza.ma-no.es/";

void setup() {
  pinMode(signalPin, OUTPUT);

  Serial.begin(9600);

  // Inicializar la pantalla OLED
  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println(F("No se detectó la pantalla OLED"));
    for (;;); // Detener si no se detecta la pantalla
  }

  // Configuración inicial de la pantalla
  display.clearDisplay();
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
}

void loop() {
  // Mostrar mensaje de solicitud de contraseña
  display.clearDisplay();
  display.setCursor(0, 0);
  display.print("Enter Password:");
  display.setCursor(0, 20);

  // Leer tecla presionada
  customKey = customKeypad.getKey();
  if (customKey) {
    Data[data_count] = customKey; 
    display.print(Data[data_count]);
    data_count++;
  }

  display.display();  // Actualizar la pantalla

  // Verificar si se ingresó toda la contraseña
  if (data_count == Password_Length - 1) {
    display.clearDisplay();
    display.setCursor(0, 0);

    // Comparar con contraseña maestra
    if (!strcmp(Data, Master)) {
      display.print("Correct");

      digitalWrite(signalPin, HIGH);  // Activar salida
      display.display();
      delay(2000);
      digitalWrite(signalPin, LOW);   // Desactivar salida

      showQRCode(url);                // Mostrar código QR
      delay(15000);
    } else {
      display.print("Incorrect");     // Mensaje de error
      delay(1000);
    }

    display.display();
    delay(1000);
    clearData();                      // Borrar datos ingresados
  }
}

// Limpia el array de datos
void clearData() {
  while (data_count != 0) {
    Data[data_count--] = 0; 
  }
  return;
}

// Genera y muestra un código QR en la pantalla OLED
void showQRCode(const char *text) {
  display.clearDisplay();

  QRCode qrcode;

  const uint8_t version = 2;
  const int scale = 2;

  uint8_t qrcodeData[qrcode_getBufferSize(version)];
  qrcode_initText(&qrcode, qrcodeData, version, ECC_LOW, text);

  int qrSize = qrcode.size * scale;

  // Verificar que el QR no sea más grande que la pantalla
  if (qrSize > SCREEN_WIDTH || qrSize > SCREEN_HEIGHT) {
    display.setCursor(0, 0);
    display.print("QR muy grande");
    display.display();
    return;
  }

  // Centrar el QR en pantalla
  int offset_x = (SCREEN_WIDTH - qrSize) / 2;
  int offset_y = (SCREEN_HEIGHT - qrSize) / 2;

  // Dibujar cada píxel del QR
  for (uint8_t y = 0; y < qrcode.size; y++) {
    for (uint8_t x = 0; x < qrcode.size; x++) {
      if (qrcode_getModule(&qrcode, x, y)) {
        display.fillRect(offset_x + x * scale, offset_y + y * scale, scale, scale, SSD1306_WHITE);
      }
    }
  }
  display.display();
}
