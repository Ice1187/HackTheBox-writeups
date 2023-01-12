## Path

### User

1. `ffuf` shows that some parenthesis can cause 500, and the error message reveals the backend is "Spring Boot".

```bash
ice1187@ice1187-lab:~/repo/htb/redpanda (master)$ ffuf -u http://$ip:8080/search -X 'POST' -H 'Content-Type: application/x-www-form-urlencoded' --data 'name=FUZZ' -w /opt/SecLists/Fuzzing/special-chars.txt  -fc 200
+                       [Status: 500, Size: 120, Words: 3, Lines: 1, Duration: 175ms]
)                       [Status: 500, Size: 120, Words: 3, Lines: 1, Duration: 197ms]
}                       [Status: 500, Size: 120, Words: 3, Lines: 1, Duration: 210ms]
{                       [Status: 500, Size: 120, Words: 3, Lines: 1, Duration: 286ms]
\                       [Status: 500, Size: 120, Words: 3, Lines: 1, Duration: 319ms]
```

2. Since it's a search function and that parenthesis will cause 500, I guess that there might be NoSQL injection.

3. Search `$` gave us `Error occured: banned characters`.

4. Search `sh` gave us `shy`'s info, so we might be in a syntax like `{ name: { $in: [ "<INPUT>" ] } }`..
