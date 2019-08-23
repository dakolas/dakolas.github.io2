from influxdb import InfluxDBClient
import serial, json
from time import sleep
from datetime import datetime

# Puerto serie del Arduino en el cual queremos recibir los datos y a cuantos baudios envia 
arduino = serial.Serial('/dev/ttyACM0', 9600)

print("Starting...")

# Ip del servidor
host = "192.168.13.131"
# Puerto donde esta levantado InfluxDB
port = 8086
# Variables de usuario y opciones de seguridad
username = ""
password = ""
ssl = True
verify_ssl = True

# Vector donde se guardan los datos recibidos
dataArray = []    #[temperature, humidity, wind_speed, wind_direction, win_direction_avg_2min, rain]
# True si se han recibido todos los datos
finished = False
# Calibrar los sensores al arrancar 
systemReady = False

# Conectarse a la base de datos
client = InfluxDBClient(host, port)

# Crear una base de datos con el nombre especificado
client.create_database("estacion")

# Obtener todas las bases de datos para comprobar que se ha creado correctamente
#print(client.get_list_database())

# Usar la base de datos que hemos creado
client.switch_database("estacion")

# Leer linea en el puerto serie, modificado para evitar problemas
def readlineCR(port):
    rv = ""
    while True:
        ch = port.read()
        
        '''
        # Testing serial port byte array stream
        for i in ch:
            print("Bytes array: " , ch)
        '''
        
        ch = ch.decode()
        #print("Type of: rv => " , type(rv), " ch => " , type(ch))
        rv  = ch
        if ch == '\r' or ch == '':
            return rv

# Bucle infinito de recepcion desde Arduino y envio a la base de datos 
def foreverReadingLoop():
    
    global finished
    global dataArray
    global systemReady
    
    
    if (systemReady == False):
        print("Esperando para calibrar sensores...")
        sleep(60)
        systemReady = True
    
    while True:
        rcv = readlineCR(arduino)
        #print("Type of: rcv => " , type(rcv))
        print(rcv)
    
        # Formatting arduino input, deleting \n, \r, making double quotes single quotes and multiple white spaces single
        
        rcv = rcv.replace('\n', '').replace('\r', '')
                
        if rcv != "Ready":
            dataArray.append(rcv)
        else:
            finished = True
        
        # Si tenemos ya todas las lecturas
        if finished == True:
                        
            # Testing
            #print("arrayLen: " , len(dataArray))
                     
            # Guardamos la hora actual para usarla como indice
            time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            
            # Json para enviar a InfluxDB
            json_body = [
                {
                    "measurement": "arduinoData",
                    "tags": {
                        "user": "Weather station",
                        "Id": "Arduino01"
                    },
                    "time": time,
                    "fields": {
                        "temperature": dataArray[0],
                        "humidity": dataArray[1],
                        "wind_speed": dataArray[2],
                        "wind_direction": dataArray[3],
                        "wind_direction_avg_2min": dataArray[4],
                        "rain": dataArray[5],
                        "temperature_dht": dataArray[6],
                        "humidity_dht": dataArray[7],
                        "co2ppm": dataArray[8],
                        "pressure": dataArray[9],
                        "altitude": dataArray[10]
                    }
                }
            ]

            # Testing
            #print(json_body)
            
            # Comprobar si hemos escrito en la base de datos correctamente
            if client.write_points(json_body) == True:
                print("Successfull writting.")
            else:
                print("Failed to write data.")
            
            # Reseteo variables
            finished = False
            dataArray = []
            
        else:
            print("Not ready yet.")
    
foreverReadingLoop()