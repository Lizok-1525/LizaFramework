#include <Keypad.h>
#include <Servo.h>

const byte ROWS = 4;
const byte COLS = 4;

char keys[ROWS][COLS] = {
  {'1','2','3','A'},
  {'4','5','6','B'},
  {'7','8','9','C'},
  {'*','0','#','D'}
};

byte rowPins[ROWS] =  {9, 8, 7, 6}; // Conecta a las filas
byte colPins[COLS] = {5, 4, 3, 2}; // Conecta a las columnas

Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);
Servo servo;

String password = "0911";
String input = "";

void setup() {
  Serial.begin(9600);
  servo.attach(10); // Conecta el servo al pin 10
  servo.write(0);   // Posición cerrada
  Serial.println("Introduce la clave:");
}

void loop() {
  char key = keypad.getKey();

  if (key) {
    Serial.print(key);

    if (key == '#') {
      if (input == password) {
        Serial.println("\n¡Clave correcta!");
        servo.write(120); // Abre la cerradura
        delay(2000);
        servo.write(0);  // Vuelve a cerrar
      } else {
        Serial.println("\nClave incorrecta");
      }
      input = ""; // Reinicia la entrada
    } else if (key == '*') {
      input = ""; // Borra entrada
      Serial.println("\nEntrada reiniciada");
    } else {
      input += key; // Agrega dígito
    }
  }
}
