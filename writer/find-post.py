import requests as rq

url = 'http://10.10.11.101/blog/post'
for i in range(-10, 256):
	uri = f'{url}/{i}'
	res = rq.get(uri)
	length = len(res.content)
	print(f'[{res.status_code}] {uri}: {length}')

