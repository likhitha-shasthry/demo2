import smtplib
import sys

from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

email = sys.argv[1]
password = sys.argv[2]

sender_email = "sinchanamj006@gmail.com"

app_password = "kweq hlna zxek seys"

subject = "Faculty Recruitment Selection"

message = f"""
Dear {email},

Congratulations!

You have been shortlisted and selected for the next stage of the Faculty Recruitment Process.

Login Credentials:

Username : {email}
Password : {password}

Please login and continue the recruitment process through the following link: http://localhost/demo2/index.php
Thank you for your interest in joining our esteemed institution. We look forward to your continued participation in the recruitment process.

Regards,
Administrator
GSSSIETW Mysuru
"""

msg = MIMEMultipart()

msg["From"] = sender_email
msg["To"] = email
msg["Subject"] = subject

msg.attach(MIMEText(message,"plain"))

server = smtplib.SMTP("smtp.gmail.com",587)

server.starttls()

server.login(sender_email,app_password)

server.send_message(msg)

server.quit()

print("success")