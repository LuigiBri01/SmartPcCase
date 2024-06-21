//COMINCIAMO CON L'INCLUDERE TUTTE LE LIBRERIE CHE ADNREMO A SFRUTTARE DURANTE TUTTO IL PROGRAMMA
//ANDREMO ANCHE A DICHIARARE TUTTE LE VARIABILI CON IL NUMERO DI PIN CHE SONO ATTACCATI SULLA ESP32 E ANCHE VARIABILI NORMALI CHE CI SERVIRANNO
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "OnePlus Nord 3 5G";
const char* password = "u3z3r8fb";

const char* serverName = "http://192.168.29.103/insert_temperature.php";

unsigned long lastTime = 0;
unsigned long timerDelay = 5000;


#include "DHT.h"
#define DHTPIN 15 

#define DHTTYPE DHT22


const int pinLED1 = 5;
const int pinLED2 = 2;
const int pinLED3 = 19;

#include <ESP32Servo.h>
Servo myservo;
int posVal=0;
int servoPin = 14;


#include <LiquidCrystal_I2C.h>
int lcdColumns = 16;
int lcdRows = 2;
LiquidCrystal_I2C lcd(0x27, lcdColumns, lcdRows); 


DHT dht(DHTPIN, DHTTYPE);

const int trigPin = 5;
const int echoPin = 18;
#define SOUND_SPEED 0.034
#define CM_TO_INCH 0.393701
long duration;
float livelloacqua;
float distanceInch;

#define Buzzer 23

//NELLA FASE DI SETUP ANDIAMO AD INIZIALIZZARE TUTTI I SENSORI CHE UTILIZZEREMO, E ANDREMO A FAR CONNETTERE LA ESP32 CON UNA RETE WIFI

void setup() {
  Serial.begin(9600);
  Serial.println(F("DHTxx test!"));

  WiFi.begin(ssid,password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading");

  dht.begin();

  pinMode (pinLED1, OUTPUT);
  pinMode (pinLED2, OUTPUT);
  pinMode (pinLED3, OUTPUT);


  lcd.init();
  lcd.backlight();

  myservo.setPeriodHertz(50);
  myservo.attach(servoPin,500,2500);

  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Input

  pinMode(Buzzer, OUTPUT);

}

void loop() {
  delay(2000);

  //INIZIALIZZIAMO UNA VARIABILE LEDSTATE CHE VARIERA' IN BASE ALL'ACCENSIONE DEL LED ON(1)-OFF(0)
  float ledstate = 0;

  //DICHIARIAMO VARIABILI CHE LEGGERENNO E CONTERRANNO I VALORI DI TEMPERATURA E DI UMIDITA' CHE ANDREMO A LEGGERE
  float h = dht.readHumidity();
  float temperatura = dht.readTemperature();
  float f = dht.readTemperature(true);

  if (isnan(h) || isnan(temperatura) || isnan(f)) {
    Serial.println(F("Failed to read from DHT sensor!"));
    return;
  }

  float hif = dht.computeHeatIndex(f, h);
  float hic = dht.computeHeatIndex(temperatura, h, false);

  //STAMPIAMO TUTTI I VALORI CHE CI SIAMO ANDATI A RICAVARE PRECEDENTEMENTE 
  Serial.print(F("Humidity: "));
  Serial.print(h);
  Serial.print(F("%  Temperature: "));
  Serial.print(temperatura);
  Serial.print(F("°C "));
  Serial.print(f);
  Serial.print(F("°F  Heat index: "));
  Serial.print(hic);
  Serial.print(F("°C "));
  Serial.print(hif);
  Serial.println(F("°F"));


  //QUI INIZIANO I CONTROLLI E COMINCIAMO A VEDERE SE LA TEMPERATURA E' MAGGIORE O MINORE DI UNA CERTA SOGLIA
  //SE MINORE ANDREMO AD ACCENDERE IL LED2 ALTRIMENTI ANDRA' SPENTO, INVECE, SE MAGGIORE IL LED1 VERRA' ACCESO 
  //E IL SERVO SI IMPOSTERA' AD ON (180 GRADI) E LO STATO DEL LED A ON(1) ALTRIMENTI IMPOSTI IL LED AD OFF(0)
  //POSI SI FA UN ULTERIORE CONTROLLO PER VEDERE SE LA TEMPERATURA E' SOTTO UNA SOGLIA E IMPOSTA NEL CASO IN 
  //CUI IL SERVO FOSSE AD ON (180 GRADI) LA IMPOSTA AD OFF(0 GRADI)
  if(temperatura<=30){
    digitalWrite (pinLED2, HIGH);
  }else{
    digitalWrite (pinLED2, LOW);
  }

  if(temperatura>30){
      digitalWrite (pinLED3, HIGH);
      ledstate = 1;

      myservo.write(180);
      delay(1000);
  }else{
    digitalWrite (pinLED3, LOW);
    ledstate = 0;
  }

  if(temperatura<=30){
     myservo.write(0);
     delay(1000);
  }

  //QUI ANDIAMO AD UTILIZZARE LA VARIABILE DELLA TEMPERATURA PRECEDENTEMENTE RICAVATA
  //E ANDIAMO A STAMPARLA SUL NOSTRO LCD CHE VISUALIZZA LA TEMPERATURA
  lcd.setCursor(0, 0);
  lcd.print("Temp: ");
  delay(1000);
  lcd.clear();
  lcd.setCursor(0,1);
  lcd.print(temperatura);
  delay(1000);
  lcd.clear();


  //ANDIAMO A FARE TUTTI I PROCESSI PER LA LETTURA DELLA DISTANZA E LA SALVIAMO NELLA VARIABILE LIVELLOACQUA
  //AVREMO IL VALORE DELLA DISTANZA IN CENTIMETRI
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  duration = pulseIn(echoPin, HIGH);
  livelloacqua = duration * SOUND_SPEED/2;
  distanceInch = livelloacqua * CM_TO_INCH;
  Serial.print("Distance (cm): ");
  Serial.println(livelloacqua);
  Serial.print("Distance (inch): ");
  Serial.println(distanceInch);

  //ANDREMO QUINDI A FARE UN CONTROLLO E VEDIAMO SE LA DISTANZA DEL SENSORE DALL'ACQUA NON AUMENTA 
  //POICHE' SE LO FA VUOL DIRE CHE C'E' UNA PERDITA DI LIQUINDI E QUINDI IL BUZZER SUONERA'
  if(livelloacqua>5){
    digitalWrite(Buzzer, HIGH);
  }else{
    digitalWrite(Buzzer, LOW);
  }


  //NELL'ULITMO PASSAGGIO ANDIAMO A PASSARE I DATI. ANDIAMO A CREARE UNA HTTPREQUESTDATA CHE CONTERRA' TUTTI 
  //GLI ELEMENTI CHE VOGLIAMO PASSARE ALL'PHP SUL NOSTRO SERVER PRIVATO.
  //ANDIAMO A CREARE UNA CONNESSIONE TRA
  if((millis() - lastTime) > timerDelay){
    if(WiFi.status()==WL_CONNECTED){
      // Creazione di un client WiFi per la connessione
      WiFiClient client;
      // Creazione di un oggetto HTTPClient per gestire le richieste HTTP
      HTTPClient http;

      // Inizializzazione della richiesta HTTP al server specificato in 'serverName'
      http.begin(client,serverName);

      http.addHeader("Content-Type","application/x-www-form-urlencoded");

      // Creazione della stringa di dati da inviare nella richiesta POST
      String httpRequestData = "temperatura=" + String(temperatura) + "&ledstate=" + String(ledstate) + "&livelloacqua=" +String(livelloacqua);
      
      // Invia la richiesta POST con i dati al server e memorizza il codice di risposta HTTP
      Serial.println(httpRequestData);
      int httpResponseCode = http.POST(httpRequestData);

      // Stampa del codice di risposta HTTP
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);

      // Chiude la connessione HTTP
      http.end();

    }else{
      Serial.println("WiFi Disconnected");
    }
    lastTime = millis();
  }
}
