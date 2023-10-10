#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>
#include "MAX30105.h"
#include <OneWire.h>
#include <DallasTemperature.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <Keypad.h>
#include <Adafruit_GFX.h>
#include <Adafruit_ST7789.h>
#include <SPI.h>

const char* ssid = "xxxxxx";
const char* password = "xxxxxx";
const char* serverUrl = "https://xxxxxxx.xxx.xx/post.php";

Adafruit_BME280 bme;
MAX30105 particleSensor;
const int oneWireBus = 5;
OneWire oneWire(oneWireBus);
DallasTemperature sensors(&oneWire);

const byte ROWS = 4;
const byte COLS = 3;
char keys[ROWS][COLS] = {
  {'1','2','3'},
  {'4','5','6'},
  {'7','8','9'},
  {'*','0','#'}
};
byte rowPins[ROWS] = {12, 14, 13, 27};
byte colPins[COLS] = {26, 25, 33};
Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);
String patientID = "";

const int buttonPin = 35;
int buttonState = LOW;
int lastButtonState = LOW;
unsigned long lastDebounceTime = 0;
unsigned long debounceDelay = 50;

#define TFT_DC 2
#define TFT_RST 4
#define TFT_CS 23

Adafruit_ST7789 tft = Adafruit_ST7789(TFT_CS, TFT_DC, TFT_RST);

bool buttonPressed = false;
bool collectingData = false;
int numReadingsToAverage = 10;
int readingsCount = 0;

float lastTemperatureBME = 0.0;
float lastUmidadeBME = 0.0;
float lastPressaoBME = 0.0;
float lastSPO2 = 0.0;
float lastBPM = 0.0;
float lastTemperatureDS18B20 = 0.0;

void setup() {


  tft.init(240, 240, SPI_MODE3);
  tft.setRotation(2);
  uint16_t time = millis();
  tft.fillScreen(ST77XX_BLACK);
  time = millis() - time;


  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
  tft.setTextSize(2);
  tft.setCursor(0, 30);
  tft.println("\nConectando ao WiFi");
    Serial.println("\nConectando ao WiFi...");
  

  }


  Serial.println("\nConectado IP:");
  Serial.println(WiFi.localIP());
  tft.fillScreen(ST77XX_BLACK); 
  tft.setTextSize(2);
  tft.setCursor(0, 15);
  tft.println("Conectado ao IP:");
  tft.setCursor(0, 40);
  tft.println(WiFi.localIP());

  if (!bme.begin(0x76)) {
    Serial.println("Não foi possível encontrar um sensor BME280, verifique a conexão!");
    while (1);
  }

  if (!particleSensor.begin(Wire, I2C_SPEED_FAST)) {
    Serial.println("Não foi possível encontrar um sensor MAX30105, verifique a conexão!");
    while (1);
  }

  particleSensor.setup();
  sensors.begin();
  Serial.println("Insira sua ID e pressione # para salvar ou * para Limpar.");
  tft.fillScreen(ST77XX_BLACK);
  tft.setCursor(0, 60);
  tft.println("Insira seu Registro:");
  tft.setTextColor(ST77XX_GREEN);
  tft.setCursor(0, 80);
  tft.println("{#} p/ salvar");
  tft.setTextColor(ST77XX_RED);
  tft.setCursor(0, 100);
  tft.println("{*} p/ limpar");
  pinMode(buttonPin, INPUT);
}

void loop() {
  char key = keypad.getKey();

  if (key) {
    if (key == '#') {
      Serial.print("Registro nº: ");
      Serial.println(patientID);
      tft.fillScreen(ST77XX_BLACK); 
      tft.setCursor(0, 15);
      tft.setTextColor(ST77XX_BLUE);
      tft.print("Registro: ");
      tft.println(patientID);

      tft.setCursor(0, 50);
      tft.setTextColor(ST77XX_GREEN);
      tft.print("Registro Salvo!");
      Serial.println("Pressione o botão para iniciar a medição.");
      tft.setTextColor(ST77XX_WHITE);
      tft.setCursor(0, 100);
      tft.println("Clique no Botao");
      tft.setCursor(0, 120);
      tft.println("para  iniciar a");
      tft.setCursor(0, 140);
      tft.println("Leitura!");
      
    } else if (key == '*') {
      // Limpar o campo de ID do paciente
      patientID = "";
      Serial.println("Registro: ");
      Serial.println("Digite novamente seu Registro");

      
    tft.fillScreen(ST77XX_BLACK);
  tft.setCursor(0, 60);
  tft.println("Novo Registro:");
  tft.setTextColor(ST77XX_GREEN);
  tft.setCursor(0, 80);
  tft.println("{#} p/ salvar");
  tft.setTextColor(ST77XX_RED);
  tft.setCursor(0, 100);
  tft.println("{*} p/ limpar");
  

    
    tft.setCursor(0, 125);
    tft.setTextColor(ST77XX_BLUE);
    tft.print("Registro: ");
    tft.println(patientID);
    tft.setCursor(0, 145);



    tft.setTextColor(ST77XX_GREEN);
    tft.print("Registro Salvo!");
    tft.setTextColor(ST77XX_WHITE);
    tft.setCursor(0, 170);

    tft.println("Clique no Botao");
    tft.setCursor(0, 190);
    tft.println("para  iniciar a");
    tft.setCursor(0, 210);
    tft.println("Leitura!");

    } else {
      patientID += key;
      Serial.print(key); // Exibe o número digitado imediatamente
    }


  }

  int reading = digitalRead(buttonPin);

  if (reading != lastButtonState) {
    lastDebounceTime = millis();
  }

  if ((millis() - lastDebounceTime) > debounceDelay) {
    if (reading != buttonState) {
      buttonState = reading;

      if (buttonState == HIGH) {
        if (!buttonPressed && !collectingData) {
          buttonPressed = true;
          Serial.println("Aguarde enquanto coletamos medições...");
           tft.fillScreen(ST77XX_BLACK);  
         
          tft.setCursor(0, 20);
          tft.println("Iniciando Leituras");
         delay(500);

          collectingData = true;
          readingsCount = 0;
          lastTemperatureBME = 0.0;
          lastUmidadeBME = 0.0;
          lastPressaoBME = 0.0;
          lastSPO2 = 0.0;
          lastBPM = 0.0;
          lastTemperatureDS18B20 = 0.0;
          Serial.println("Medições:");

        }
      }
    }
  }






  if (collectingData) {
    float temperaturaBME = bme.readTemperature();
    float umidadeBME = bme.readHumidity();
    float pressaoBME = bme.readPressure() / 100.0F;
    particleSensor.readTemperature();
    uint32_t bpm = particleSensor.getIR();
    uint32_t spo2 = particleSensor.getRed();
    sensors.requestTemperatures();
    float temperaturaDS18B20 = sensors.getTempCByIndex(0);

    if (!isnan(temperaturaBME) && !isnan(umidadeBME) && !isnan(pressaoBME) && !isnan(bpm) && !isnan(spo2) && !isnan(temperaturaDS18B20)) {
      lastTemperatureBME = temperaturaBME;
      lastUmidadeBME = umidadeBME;
      lastPressaoBME = pressaoBME;
      lastSPO2 = spo2;
      lastBPM = bpm;
      lastTemperatureDS18B20 = temperaturaDS18B20;
      readingsCount++;

      Serial.print("Medições ");
      Serial.print(readingsCount);
      Serial.println(":");

      tft.fillScreen(ST77XX_BLACK);  
      tft.setCursor(0, 15);
      tft.setTextColor(ST77XX_MAGENTA);
      tft.print("Leitura:");
      tft.setTextColor(ST77XX_BLUE);
      tft.print(readingsCount);

      Serial.print("BME280 - Temp. Ambiente: ");
      Serial.print(temperaturaBME);

      tft.setTextColor(ST77XX_WHITE);
      tft.setCursor(0, 40);
      tft.print("Temp. Ambt:");
      tft.setTextColor(ST77XX_BLUE);
      tft.print(temperaturaBME);
      tft.println(" C");

      Serial.print(" °C, Umidade do Ar: ");
      Serial.print(umidadeBME);

      tft.setTextColor(ST77XX_WHITE);
      tft.setCursor(0, 60);
      tft.print("Umidade:");
      tft.setTextColor(ST77XX_BLUE);
      tft.print(umidadeBME);

      Serial.print(" %, Pressão Atmosférica: ");
      Serial.print(pressaoBME);
      Serial.println(" hPa");
 
      tft.setTextColor(ST77XX_WHITE);
      tft.setCursor(0, 80);
      tft.print("Pressao:");
      tft.setTextColor(ST77XX_BLUE);
      tft.print(pressaoBME);
      Serial.println(" hPa");

      Serial.print("MAX30105 - Saturação: ");
      Serial.print(spo2 / 1000.0);

      tft.setTextColor(ST77XX_WHITE);
      tft.setCursor(0, 100);
      tft.print("SpO2:");
      tft.setTextColor(ST77XX_BLUE);
      tft.print(spo2 / 1000.0);

      Serial.print(", Batimentos: ");
      Serial.print(bpm / 1000.0);
      Serial.println();

      tft.setTextColor(ST77XX_WHITE);
      tft.setCursor(0, 120);
      tft.print("SpO2:");
      tft.setTextColor(ST77XX_BLUE);
      tft.print(bpm / 1000.0);


      Serial.print("DS18B20 - Temp. Corporal: ");
      Serial.print(temperaturaDS18B20);
      Serial.println(" C");

      tft.setTextColor(ST77XX_WHITE);
      tft.setCursor(0, 140);
      tft.print("Temp. Corp:");
      tft.setTextColor(ST77XX_BLUE);
      tft.print(temperaturaDS18B20);
      tft.println(" C");

    }

    if (readingsCount >= numReadingsToAverage) {
      String httpRequestData = "patientID=" + patientID +
                               "&temperaturaBME=" + String(lastTemperatureBME) +
                               "&umidadeBME=" + String(lastUmidadeBME) +
                               "&pressaoBME=" + String(lastPressaoBME) +
                               "&spo2=" + String(lastSPO2 / 1000.0) +
                               "&bpm=" + String(lastBPM / 1000.0) +
                               "&temperaturaDS18B20=" + String(lastTemperatureDS18B20);

      HTTPClient http;
      http.begin(serverUrl);
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      int httpResponseCode = http.POST(httpRequestData);

      if (httpResponseCode > 0) {
        if (httpResponseCode == HTTP_CODE_OK) {
          Serial.println("Solicitação HTTP bem-sucedida.");
          String response = http.getString();
          Serial.println(response);

         tft.fillScreen(ST77XX_BLACK); 
          tft.setCursor(0, 20);
          tft.setTextColor(ST77XX_GREEN);
          tft.print("Leituras Enviadas");
          tft.setCursor(0, 45);
          tft.print("com Sucesso!");
         delay(1000);



        } else {
          Serial.print("Erro na solicitação HTTP. Código de erro: ");
          Serial.println(httpResponseCode);

        tft.fillScreen(ST77XX_BLACK);
        tft.setCursor(20, 90);
         tft.setTextColor(ST77XX_RED);
        tft.print("Erro de envio");
       delay(1000);


        }
      } else {
        Serial.println("Erro na conexão ao servidor.");

        tft.fillScreen(ST77XX_BLACK);
         tft.setCursor(20, 90);
        tft.setTextColor(ST77XX_RED);
        tft.print("Server Error");
      delay(1000);
      }

      http.end();
      collectingData = false;
      buttonPressed = false;
      Serial.println("Insira sua ID e pressione # para salvar ou * para Limpar.");

      tft.fillScreen(ST77XX_BLACK);
      tft.setCursor(0, 15);
      tft.setTextColor(ST77XX_WHITE);
      tft.println("Insira seu Registro:");
      tft.setTextColor(ST77XX_GREEN);
      tft.setCursor(0, 50);
      tft.println("{#} p/ salvar");
      tft.setTextColor(ST77XX_RED);
      tft.setCursor(0, 70);
      tft.println("{*} p/ limpar");


      patientID = "";


    }
  }

  lastButtonState = reading;
}

