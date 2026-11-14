import urllib.request
import json
import re

req = urllib.request.Request("http://127.0.0.1:8080/", headers={"User-Agent": "Mozilla/5.0"})
with urllib.request.urlopen(req) as resp:
    html = resp.read().decode('utf-8')

scripts = re.findall(r'<script\s+type=[\'"]application/ld\+json[\'"][^>]*>(.*?)</script>', html, re.DOTALL | re.IGNORECASE)
print(f"Number of ld+json scripts found: {len(scripts)}")
data = json.loads(scripts[0])
print(json.dumps(data, indent=2))
