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

const int ledVerde = 11;  // Pin LED verde
const int ledRojo = 12;   // Pin LED rojo

String password = "A321";
String input = "";

void setup() {
  Serial.begin(9600);
  servo.attach(10); // Conecta el servo al pin 10
  servo.write(0);   // Posición cerrada
  
  pinMode(ledVerde, OUTPUT);
  pinMode(ledRojo, OUTPUT);
  
  digitalWrite(ledVerde, LOW);
  digitalWrite(ledRojo, LOW);
  
  Serial.println("Introduce la clave:");
}

void loop() {
  char key = keypad.getKey();

  if (key) {
    Serial.print(key);

    if (key == '#') {
      if (input == password) {
        Serial.println("\n¡Clave correcta!");
        digitalWrite(ledVerde, HIGH);  // Enciende LED verde
        digitalWrite(ledRojo, LOW);
        
        servo.write(120); // Abre la cerradura
        delay(2000);
        servo.write(0);  // Vuelve a cerrar
        
        digitalWrite(ledVerde, LOW);   // Apaga LED verde
      } else {
        Serial.println("\nClave incorrecta");
        digitalWrite(ledRojo, HIGH);   // Enciende LED rojo
        digitalWrite(ledVerde, LOW);
        delay(2000);
        digitalWrite(ledRojo, LOW);    // Apaga LED rojo
      }
      input = ""; // Reinicia la entrada
    } else if (key == '*') {
      input = ""; // Borra entrada
      Serial.println("\nEntrada reiniciada");
      digitalWrite(ledRojo, LOW);
      digitalWrite(ledVerde, LOW);
    } else {
      input += key; // Agrega dígito
    }
  }
}
