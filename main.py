
import cv2
import time
import torch
from ultralytics import YOLO
import logging
import os

# 🔇 ปิด log ของ YOLO
os.environ["ULTRALYTICS_LOGGING"] = "False"
logging.getLogger("ultralytics").setLevel(logging.WARNING)

# โหลดโมเดลที่เทรนแล้ว
model = YOLO('detectModel.pt')
model.to(torch.device('cpu'))



# เปิดกล้อง
cap = cv2.VideoCapture(0)

print("ระบบเริ่มทำงาน...")

while True:
    if ser.in_waiting:
        line = ser.readline().decode().strip()
        print(f"[จาก Arduino] {line}")
        
        if line == "DETECTED":
            print("🔍 มีวัตถุ → กำลังตรวจด้วย AI")

            ret, frame = cap.read()
            if not ret:
                print("ไม่พบกล้อง")
                continue

            # วิเคราะห์ด้วย YOLO
            results = model(frame)[0]

            # ดึงชื่อคลาส
            classes = results.boxes.cls.tolist()
            labels = [model.names[int(cls)] for cls in classes]

            print(f"ผล AI: {labels}")

            # ถ้าเจอ plastic bottle
            if "bottle-plastic" in labels:
                print("✅ เจอขวดพลาสติก → ส่งไปยัง Arduino")
                ser.write(b'BOTTLE\n')
            else:
                print("❌ ไม่ใช่ขวดพลาสติก")

    # กด Q เพื่อออก
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# ปิดกล้องและพอร์ต
cap.release()
ser.close()
