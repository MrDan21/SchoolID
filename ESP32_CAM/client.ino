
#include "esp_camera.h"
#include "img_converters.h"
#include <TFT_eSPI.h> 
#include <WiFiManager.h>
//#include <WiFiClientSecure.h>
#include <WiFi.h>
#include <Wire.h>
#include <SPI.h>
#include "esp_http_server.h"
#include <HTTPClient.h>

#define PWDN_GPIO_NUM     32
#define RESET_GPIO_NUM    -1
#define XCLK_GPIO_NUM      0
#define SIOD_GPIO_NUM     26
#define SIOC_GPIO_NUM     27
#define Y9_GPIO_NUM       35
#define Y8_GPIO_NUM       34
#define Y7_GPIO_NUM       39
#define Y6_GPIO_NUM       36
#define Y5_GPIO_NUM       21
#define Y4_GPIO_NUM       19
#define Y3_GPIO_NUM       18
#define Y2_GPIO_NUM        5
#define VSYNC_GPIO_NUM    25
#define HREF_GPIO_NUM     23
#define PCLK_GPIO_NUM     22

#define BOTTON 15
#define FLASH_LED 4
#define BOTTON2 16

//  PWM
#define PWM_CHANNEL 7
#define PWM_FREQ 5000
#define PWM_RESOLUTION 8   // 0-255

int cont=0,vand=0;

TaskHandle_t Task1;

TFT_eSPI tft = TFT_eSPI();       // Invoke custom library
TFT_eSprite spr = TFT_eSprite(&tft);

camera_config_t config;

uint16_t *scr;
long initalTime = 0;
long frameTime = 1;
volatile bool screenRefreshFlag = true;
//------------------------------------------------------------
void enviarDatos(camera_fb_t *storedFb) {
 WiFiClient client;

  const char* host = "aqui pon tu ip del servidor";
  const int port = 80;
 
    Serial.println(WiFi.status());
    Serial.println(WiFi.localIP());

    Serial.println("Conectando...");

     
    if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi desconectado");
    wifi_init();
    return;
  }
  Serial.println("Conectando!!");
   // client.connect(host, port)
     if (!client.connect(host, port)) {
        Serial.println("Error de conexion");
        return;
    }
    Serial.println("Conectado al servidor");
     String url = "aqui la direccion de la api"; 
  // multipart
     String boundary = "----ESP32CAMBoundary";

  String bodyStart =
    "--" + boundary + "\r\n"
    "Content-Disposition: form-data; name=\"photo\"; filename=\"cam.jpg\"\r\n"
    "Content-Type: image/jpeg\r\n\r\n";

  String bodyEnd =
    "\r\n--" + boundary + "--\r\n";
    int contentLength = bodyStart.length() + storedFb->len + bodyEnd.length();
    //--------------------------------

  client.println("POST " + url + " HTTP/1.1");
  client.println("Host: ip del servidor");

  client.println("Authorization: Bearer (contraseña para acceder a la api)");
 // client.println("Content-Type: image/jpeg");
  client.println("Content-Type: multipart/form-data; boundary=" + boundary);
  client.println("Connection: close");
  client.print("Content-Length: ");
  //client.println(storedFb->len);
  client.println(contentLength);
  

  client.println();

  size_t totalSent = 0;
  uint8_t *fbBuf = storedFb->buf;
  size_t fbLen = storedFb->len;
  client.print(bodyStart);
  while (fbLen > 0) {
  size_t chunkSize = fbLen;

  if (chunkSize > 1024) {
    chunkSize = 1024;
  }

  size_t sent = client.write(fbBuf, chunkSize);

  if (sent == 0) {
    Serial.println("Error enviando chunk");
    break;
  }

  totalSent += sent;
  fbBuf += sent;
  fbLen -= sent;

  delay(1);
  }
  client.print(bodyEnd);

  Serial.print("Bytes totales enviados: ");
  Serial.println(totalSent);


  unsigned long timeout = millis();

  while (millis() - timeout < 5000) {
    while (client.available()) {
      char c = client.read();
      Serial.write(c);
      timeout = millis();
    }
    delay(1);
  }


  client.stop();
  Serial.println("Conexion cerrada");
  return;


}


void wifi_init(){
  
  WiFiManager wm;
  
  //wm.resetSettings();
  wm.setConfigPortalTimeout(180); 

  bool res;
  res = wm.autoConnect("ESPcamr","password"); 
  
  if(!res) {
        Serial.println("fallo de conexion");
  
         ESP.restart();
         
    }
    else {
          
        Serial.println("conectado!!");
      
    } 


}



/////////////////////////////////
void Task1code( void * pvParameters ) {
  //core0 setup
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = Y2_GPIO_NUM;
  config.pin_d1 = Y3_GPIO_NUM;
  config.pin_d2 = Y4_GPIO_NUM;
  config.pin_d3 = Y5_GPIO_NUM;
  config.pin_d4 = Y6_GPIO_NUM;
  config.pin_d5 = Y7_GPIO_NUM;
  config.pin_d6 = Y8_GPIO_NUM;
  config.pin_d7 = Y9_GPIO_NUM;
  config.pin_xclk = XCLK_GPIO_NUM;
  config.pin_pclk = PCLK_GPIO_NUM;
  config.pin_vsync = VSYNC_GPIO_NUM;
  config.pin_href = HREF_GPIO_NUM;
  config.pin_sscb_sda = SIOD_GPIO_NUM;
  config.pin_sscb_scl = SIOC_GPIO_NUM;
  config.pin_pwdn = PWDN_GPIO_NUM;
  config.pin_reset = RESET_GPIO_NUM;
  config.xclk_freq_hz = 20000000;
  //formato rgb
  config.frame_size = FRAMESIZE_240X240;
  //--------config.frame_size = FRAMESIZE_QVGA; // 320x240 
  //config.pixel_format = PIXFORMAT_RGB565;
   //config.fb_count = 2; 
  // formato jpeg
  //config.frame_size = FRAMESIZE_QVGA;
  config.pixel_format = PIXFORMAT_JPEG;
  config.jpeg_quality = 12;
  config.fb_count = 1;

  config.grab_mode = CAMERA_GRAB_LATEST;    //option CAMERA_GRAB_WHEN_EMPTY
  config.fb_location = CAMERA_FB_IN_PSRAM;
  config.jpeg_quality = 12;
                          //need more than 1 for latest grab

  
  // camera init
  esp_err_t err = esp_camera_init(&config);
  if (err != ESP_OK) {
    Serial.printf("error al inicializar la camara: 0x%x", err);
    return;
  }
  sensor_t * s = esp_camera_sensor_get();
  s->set_brightness(s, 0);     // -2 to 2
  s->set_contrast(s, 0);       // -2 to 2
  s->set_saturation(s, 0);     // -2 to 2
  s->set_special_effect(s, 0); // 0 to 6 (0 - No Effect, 1 - Negative, 2 - Grayscale, 3 - Red Tint, 4 - Green Tint, 5 - Blue Tint, 6 - Sepia)
  s->set_whitebal(s, 1);       // 0 = disable , 1 = enable
  s->set_awb_gain(s, 1);       // 0 = disable , 1 = enable
  s->set_wb_mode(s, 0);        // 0 to 4 - if awb_gain enabled (0 - Auto, 1 - Sunny, 2 - Cloudy, 3 - Office, 4 - Home)
  s->set_exposure_ctrl(s, 1);  // 0 = disable , 1 = enable
  s->set_aec2(s, 0);           // 0 = disable , 1 = enable
  s->set_ae_level(s, 0);       // -2 to 2
  s->set_aec_value(s, 300);    // 0 to 1200
  s->set_gain_ctrl(s, 1);      // 0 = disable , 1 = enable
  s->set_agc_gain(s, 0);       // 0 to 30
  s->set_gainceiling(s, (gainceiling_t)0);  // 0 to 6
  s->set_bpc(s, 0);            // 0 = disable , 1 = enable
  s->set_wpc(s, 1);            // 0 = disable , 1 = enable
  s->set_raw_gma(s, 1);        // 0 = disable , 1 = enable
  s->set_lenc(s, 1);           // 0 = disable , 1 = enable
  s->set_hmirror(s, 0);        // 0 = disable , 1 = enable
  s->set_vflip(s, 0);          // 0 = disable , 1 = enable
  s->set_dcw(s, 1);            // 0 = disable , 1 = enable
  s->set_colorbar(s, 0);       // 0 = disable , 1 = enable

  //core0 loop
  for (;;) {
    
    //toma captura
    camera_fb_t  * fb = NULL;
    fb = esp_camera_fb_get();
    if (!fb) {
    Serial.println("Error: fb NULL");
    continue;
  }
  
  if(digitalRead(BOTTON) == LOW) { 
    
    if(cont>=10){
      cont=0;
      if(digitalRead(BOTTON) == LOW){
        Serial.println("Enviando JPEG");
        enviarDatos(fb);

        esp_camera_fb_return(fb);
        continue;
       
      }else{
        Serial.println("se solto boton");

      }
    }
  }
  cont++;
  delay(50);
   
  // convertir JPEG a RGB565 para TFT
  bool ok = jpg2rgb565(
  fb->buf,
  fb->len,
  (uint8_t*)scr,
  JPG_SCALE_NONE
  );
  for (int i = 0; i < 240 * 240; i++) {
    scr[i] = (scr[i] << 8) | (scr[i] >> 8);
  }

  if (ok) {
  screenRefreshFlag = true;
  } else {
  Serial.println("Error convirtiendo JPEG a RGB565");
  }

  esp_camera_fb_return(fb);
  vTaskDelay(1);

  } // cierra for(;;)

} // cierra Task1code
//////////////////////////////////////////////////////////
void setup() {
  Serial.begin(115200);
  pinMode(BOTTON, INPUT_PULLUP);
  // pinMode(BOTTON2, INPUT_PULLUP);
  // configurar PWM
  ledcSetup(PWM_CHANNEL, PWM_FREQ, PWM_RESOLUTION);

  // conectar GPIO4 al canal PWM
  ledcAttachPin(FLASH_LED, PWM_CHANNEL);


  Serial.setDebugOutput(true);
  Serial.println("prueba de comunicacion");
  tft.init();
  tft.setSwapBytes(true);
  tft.setRotation(3);
  tft.fillScreen(TFT_BLACK);
  tft.setTextColor(TFT_BLACK, TFT_WHITE);
  scr = (uint16_t*)spr.createSprite(240, 240);
  tft.drawString("Cargando...", 105, 105, 2);
  tft.drawString("conectando a internet", 155, 105, 2);
  tft.setRotation(1);

//----------------
  wifi_init();
 //------------------------------ 

  xTaskCreatePinnedToCore(
    Task1code,   // Task function.
    "Task1",     // name of task.
    100000,      // Stack size of task
    NULL,        // parameter of the task
    1,           // priority of the task
    &Task1,      // Task handle to keep track of created task
    0);          // pin task to core 0

  delay(1000); 
}

//////////////////////////////////
void loop() {
  //refresh display if there is a new image from the camera
  if (screenRefreshFlag) {
   // initalTime = millis();
    //spr.drawString(String(frameTime), 5, 5, 2);   //  100,220print frame time in milliseconds
    //spr.drawString("ms", 50, 5, 2);  //125,220
    spr.pushSprite(0, 0);
    screenRefreshFlag = false;
    //frameTime = millis() - initalTime;
  }
/*
   if(digitalRead(BOTTON) == LOW) { 
    
    if(cont>=10){
      cont=0;
      if(digitalRead(BOTTON) == LOW && vand==0){
        vand=1;
           ledcWrite(PWM_CHANNEL, 128);
        //delay(200); 
      }else if(vand==1){
        ledcWrite(PWM_CHANNEL, 0);
        vand=0;

      }
    }
  }
  cont++;
delay(50); 
  */
}



