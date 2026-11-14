import urllib.request
import json
import re

BASE_URL = "http://127.0.0.1:8080"

test_urls = [
    f"{BASE_URL}/products/custom-uv-dtf-decal-sheet",
    f"{BASE_URL}/products/streetwear-oversized-tee",
    f"{BASE_URL}/blog/uv-dtf-printing-product-branding",
    f"{BASE_URL}/blog/different-t-shirt-printing-techniques"
]

for url in test_urls:
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    with urllib.request.urlopen(req) as resp:
        html = resp.read().decode('utf-8')
    scripts = re.findall(r'<script\s+type=[\'"]application/ld\+json[\'"][^>]*>(.*?)</script>', html, re.DOTALL | re.IGNORECASE)
    assert len(scripts) == 1, f"Expected 1 script on {url}, found {len(scripts)}"
    data = json.loads(scripts[0])
    types = [node.get("@type") for node in data["@graph"]]
    print(f"URL: {url} -> Types in @graph: {types}")

print("All extra dynamic product and blog URLs validated successfully!")
