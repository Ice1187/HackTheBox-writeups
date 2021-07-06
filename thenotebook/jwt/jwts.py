import jwt

with open('key', 'r') as f:
	pri = f.read()

with open('key.pub', 'r') as f:
	pub = f.read()

'''
{"typ":"JWT","alg":"RS256","kid":"http://localhost:7070/privKey.key"}{"username":"ice1187","email":"ice1187@ice.com","admin_cap":false}
'''

enc = jwt.encode({"username":"ice1187","email":"ice1187@ice.com","admin_cap":True}, pri, algorithm='RS256', headers={"kid":"http://10.10.16.10:13338/key.pub"}) 
print(enc)
