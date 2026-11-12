import re
import html

with open(r'c:\Movies\Internship\OrderPrintex\scratch\extracted_prompt.txt', 'r', encoding='utf-8') as f:
    text = f.read()

sections = re.split(r'={5,}\n(\d+\..*?)\n={5,}', text)
content = sections[18]
parts = re.split(r'--- BLOG ARTICLE \d+ ---', content)

def parse_article(raw_text):
    lines = raw_text.strip().split('\n')
    meta = {}
    body_lines = []
    
    in_meta = True
    for line in lines:
        stripped = line.strip()
        if in_meta:
            if stripped.startswith('Meta Title:'):
                meta['meta_title'] = stripped[len('Meta Title:'):].strip()
            elif stripped.startswith('Meta Description:'):
                meta['meta_description'] = stripped[len('Meta Description:'):].strip()
            elif stripped.startswith('URL slug:'):
                meta['slug'] = stripped[len('URL slug:'):].strip().strip('/')
                if meta['slug'].startswith('blog/'):
                    meta['slug'] = meta['slug'][len('blog/'):]
            elif stripped.startswith('Primary keyword:'):
                meta['primary_keyword'] = stripped[len('Primary keyword:'):].strip()
            elif stripped.startswith('Secondary keywords:'):
                meta['secondary_keywords'] = stripped[len('Secondary keywords:'):].strip()
            elif stripped.startswith('Author:'):
                meta['author'] = stripped[len('Author:'):].strip()
            elif stripped.startswith('H1:'):
                meta['title'] = stripped[len('H1:'):].strip()
                in_meta = False
            else:
                pass
        else:
            body_lines.append(line)
            
    return meta, body_lines

m1, b1 = parse_article(parts[1])
print("Article 1 Meta:", m1)
m2, b2 = parse_article(parts[2])
if 'slug' not in m2:
    m2['slug'] = 'uv-dtf-printing-product-branding'
print("Article 2 Meta:", m2)
m3, b3 = parse_article(parts[3])
if 'slug' not in m3:
    m3['slug'] = 'different-t-shirt-printing-techniques'
print("Article 3 Meta:", m3)

