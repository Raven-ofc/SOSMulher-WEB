#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// --- Configurações da rede ---
const char* ssid     = "SEU_WIFI";
const char* password = "SUA_SENHA";

// --- Configurações da API ---
// Troque SEU_IP pelo IP da máquina rodando "php artisan serve --host=0.0.0.0"
// e "1" pelo id da tornozeleira cadastrada no banco (tabela tbtornozeleira)
const char* apiUrl = "http://SEU_IP:8000/api/tornozeleiras/1/localizacoes";

// Intervalo entre envios (30 segundos)
const unsigned long INTERVALO_ENVIO = 30000;
unsigned long ultimoEnvio = 0;

void setup() {
  Serial.begin(115200);

  WiFi.begin(ssid, password);
  Serial.print("Conectando ao WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi conectado!");
  Serial.println(WiFi.localIP());
}

void loop() {
  // Aqui entra o seu código já existente do TinyGPS++
  // supondo que você já tem as variáveis:
  //   double latitude  = gps.location.lat();
  //   double longitude = gps.location.lng();
  //   bool gpsValido   = gps.location.isValid();

  if (millis() - ultimoEnvio >= INTERVALO_ENVIO) {
    ultimoEnvio = millis();

    // Substitua pelas variáveis reais do seu parsing do GPS
    double latitude = -23.549608;
    double longitude = -46.419186;
    bool gpsValido = true;

    if (gpsValido) {
      enviarLocalizacao(latitude, longitude);
    } else {
      Serial.println("GPS ainda sem fix válido, aguardando...");
    }
  }
}

void enviarLocalizacao(double latitude, double longitude) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi desconectado, não é possível enviar.");
    return;
  }

  HTTPClient http;
  http.begin(apiUrl);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");

  // Monta o JSON com ArduinoJson
  JsonDocument doc;
  doc["latitudeLocalizacao"] = latitude;
  doc["longitudeLocalizacao"] = longitude;

  String jsonBody;
  serializeJson(doc, jsonBody);

  int httpCode = http.POST(jsonBody);

  if (httpCode > 0) {
    String resposta = http.getString();
    Serial.printf("HTTP %d\n", httpCode);
    Serial.println(resposta);
  } else {
    Serial.printf("Erro ao enviar: %s\n", http.errorToString(httpCode).c_str());
  }

  http.end();
}
