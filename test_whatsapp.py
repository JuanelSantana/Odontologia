import requests
import json

def send_test_message():
    url = "http://localhost:8080/message/sendText/clinica_cepin"
    
    headers = {
        "apikey": "G5K34EFR55BB2YJH2AS72RB8",
        "Content-Type": "application/json"
    }
    
    payload = {
        "number": "18092684228",
        "text": "Hola paciente. Esta es una prueba del nuevo sistema de Clinica Dr. Cepín."
    }
    
    print("==================================================")
    print("Enviando mensaje de prueba vía Evolution API...")
    print(f"URL: {url}")
    print(f"Headers: {json.dumps(headers, indent=2)}")
    print(f"Body: {json.dumps(payload, indent=2)}")
    print("==================================================")
    
    try:
        response = requests.post(url, json=payload, headers=headers, timeout=30)
        
        print(f"Código de Estado: {response.status_code}")
        print("Respuesta de la API:")
        try:
            print(json.dumps(response.json(), indent=2, ensure_ascii=False))
        except ValueError:
            print(response.text)
            
        if response.status_code in [200, 201]:
            print("\n✅ ¡Mensaje enviado con éxito!")
        else:
            print("\n❌ Error en la respuesta del servidor.")
            
    except requests.exceptions.ConnectionError:
        print("\n❌ Error de conexión: No se pudo conectar a la Evolution API.")
        print("Asegúrate de que el contenedor de Docker esté corriendo en el puerto 8080.")
    except Exception as e:
        print(f"\n❌ Ocurrió un error inesperado: {e}")
        
    print("==================================================")

if __name__ == "__main__":
    send_test_message()
