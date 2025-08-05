import serial
import mysql.connector

# Setup serial
ser = serial.Serial('COM12', 9600)  # Replace with your port

# Setup database
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="moisture_db"
)
cursor = db.cursor()

while True:
    try:
        line = ser.readline().decode().strip()
        values = line.split(',')

        if len(values) >= 7:
            moisture = float(values[0])
            min_val = float(values[1])
            max_val = float(values[2])
            avg_val = float(values[3])
            status = values[4]
            temp = float(values[5])
            device_id = int(values[6])  # Send this from Arduino or use default

            sql = """INSERT INTO moisture_readings
                (device_id, moisture_percentage, moisture_min, moisture_max, moisture_avg, gauge_status, temperature)
                VALUES (%s, %s, %s, %s, %s, %s, %s)"""
            cursor.execute(sql, (device_id, moisture, min_val, max_val, avg_val, status, temp))
            db.commit()
    except Exception as e:
        print("Error:", e)
