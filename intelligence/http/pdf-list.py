import requests as rq

url = 'http://10.10.10.248/documents'
for m in range(1, 13):
	for d in range(1, 32):
		month = f'0{m}'[-2:]
		day = f'0{d}'[-2:]
		# name = f'2020-{month}-{day}-upload.pdf'
		name = f'2019-{month}-{day}-upload.pdf'
		
		uri = f'{url}/{name}'
		res = rq.get(uri)
		print(f'{name}: {res.status_code}')
		if res.status_code == 200:
			with open(f'./{name}', 'wb') as f:
				f.write(res.content)

		
