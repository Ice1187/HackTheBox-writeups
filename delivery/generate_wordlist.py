import itertools

with open ('./root_pw', 'r') as f:
	pw = f.read().strip()

print(f'[*] Generating wordlist of {pw}')
wordlist = '\n'.join(list(map(''.join, itertools.product(*zip(pw.upper(), pw.lower())))))

wordlist_filename = 'wordlist.txt'
print(f'[+] Wordlist -> {wordlist_filename}')
with open(wordlist_filename, 'w') as f:
	f.write(wordlist)


