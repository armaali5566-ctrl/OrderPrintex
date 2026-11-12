import re
import json

with open(r'c:\Movies\Internship\OrderPrintex\scratch\extracted_prompt.txt', 'r', encoding='utf-8') as f:
    text = f.read()

sections = re.split(r'={5,}\n(\d+\..*?)\n={5,}', text)
content = sections[18]
parts = re.split(r'--- BLOG ARTICLE \d+ ---', content)

# Article 1
art1_raw = parts[1].strip()
# Article 2
art2_raw = parts[2].strip()
# Article 3
art3_raw = parts[3].strip()

print("Art 1 lines:", len(art1_raw.split('\n')))
print("Art 2 lines:", len(art2_raw.split('\n')))
print("Art 3 lines:", len(art3_raw.split('\n')))

