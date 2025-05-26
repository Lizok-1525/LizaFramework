#include <Wire.h> 
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <Keypad.h>
#include <qrcode.h>

#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET     -1
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

#define Password_Length 8 

int signalPin = 12;

char Data[Password_Length]; 
char Master[Password_Length] = "1234561"; 
byte data_count = 0;
bool Pass_is_good;
char customKey;


const byte ROWS = 4;
const byte COLS = 4;
char hexaKeys[ROWS][COLS] = {
  {'1', '2', '3', 'A'},
  {'4', '5', '6', 'B'},
  {'7', '8', '9', 'C'},
  {'*', '0', '#', 'D'}
};

byte rowPins[ROWS] = {9, 8, 7, 6};
byte colPins[COLS] = {5, 4, 3, 2};

Keypad customKeypad = Keypad(makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS);

const char *url = "https://liza.ma-no.es/";

void setup(){
  pinMode(signalPin, OUTPUT);

  Serial.begin(9600);
  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println(F("No se detectó la pantalla OLED"));
    for (;;);
  }

  display.clearDisplay();
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
}

void loop(){

  display.clearDisplay();
  display.setCursor(0, 0);
  display.print("Enter Password:");
  display.setCursor(0, 20);

  customKey = customKeypad.getKey();
  if (customKey){
    Data[data_count] = customKey; 
    display.print(Data[data_count]);
    data_count++;
    }

     display.display();  // Actualiza la pantalla

  if (data_count == Password_Length - 1) {
    display.clearDisplay();
    display.setCursor(0, 0);

    if (!strcmp(Data, Master)) {
      display.print("Correct");
     
      digitalWrite(signalPin, HIGH);
       display.display();
      delay(2000);
      digitalWrite(signalPin, LOW);
         showQRCode(url);
         delay(10000);
    } else {
      display.print("Incorrect");
      delay(1000);
    }

    display.display();
    delay(1000);
    clearData();
  }
}

void clearData(){
  while(data_count !=0){
    Data[data_count--] = 0; 
  }
  return;
}

void showQRCode(const char *text) {
  display.clearDisplay();

  QRCode qrcode;

  const uint8_t version = 2;
  const int scale = 2;

  uint8_t qrcodeData[qrcode_getBufferSize(version)];
  qrcode_initText(&qrcode, qrcodeData, version, ECC_LOW, text);

  int qrSize = qrcode.size * scale;

  // Protege contra desbordamiento
  if (qrSize > SCREEN_WIDTH || qrSize > SCREEN_HEIGHT) {
    display.setCursor(0, 0);
    display.print("QR muy grande");
    display.display();
    return;
  }

  int offset_x = (SCREEN_WIDTH - qrSize) / 2;
  int offset_y = (SCREEN_HEIGHT - qrSize) / 2;

  for (uint8_t y = 0; y < qrcode.size; y++) {
    for (uint8_t x = 0; x < qrcode.size; x++) {
      if (qrcode_getModule(&qrcode, x, y)) {
        display.fillRect(offset_x + x * scale, offset_y + y * scale, scale, scale, SSD1306_WHITE);
      }
    }
  }
  display.display();
}