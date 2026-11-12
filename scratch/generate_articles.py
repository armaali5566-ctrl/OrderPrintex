import re
import json

with open(r'c:\Movies\Internship\OrderPrintex\scratch\extracted_prompt.txt', 'r', encoding='utf-8') as f:
    text = f.read()

sections = re.split(r'={5,}\n(\d+\..*?)\n={5,}', text)
content = sections[18]
parts = re.split(r'--- BLOG ARTICLE \d+ ---', content)

# ----------------- ARTICLE 1 -----------------
art1_text = parts[1].strip()
lines1 = art1_text.split('\n')
meta1 = {}
body_lines1 = []
in_meta1 = True
for l in lines1:
    s = l.strip()
    if in_meta1:
        if s.startswith('Meta Title:'): meta1['meta_title'] = s[len('Meta Title:'):].strip()
        elif s.startswith('Meta Description:'): meta1['meta_description'] = s[len('Meta Description:'):].strip()
        elif s.startswith('URL slug:'): meta1['slug'] = 'dtf-printing-technology-custom-apparel'
        elif s.startswith('H1:'):
            meta1['title'] = s[len('H1:'):].strip()
            in_meta1 = False
    else:
        body_lines1.append(l)

# ----------------- ARTICLE 2 -----------------
art2_text = parts[2].strip()
lines2 = art2_text.split('\n')
meta2 = {'slug': 'uv-dtf-printing-product-branding'}
body_lines2 = []
in_meta2 = True
for l in lines2:
    s = l.strip()
    if in_meta2:
        if s.startswith('Meta Title:'): meta2['meta_title'] = s[len('Meta Title:'):].strip()
        elif s.startswith('Meta Description:'): meta2['meta_description'] = s[len('Meta Description:'):].strip()
        elif s.startswith('H1:'):
            meta2['title'] = s[len('H1:'):].strip()
            in_meta2 = False
    else:
        body_lines2.append(l)

# ----------------- ARTICLE 3 -----------------
art3_text = parts[3].strip()
lines3 = art3_text.split('\n')
meta3 = {'slug': 'different-t-shirt-printing-techniques', 'author': 'By Printex'}
body_lines3 = []
in_meta3 = True
for l in lines3:
    s = l.strip()
    if in_meta3:
        if s.startswith('H1:'):
            meta3['title'] = s[len('H1:'):].strip()
        elif s.startswith('Author:'):
            meta3['author'] = s[len('Author:'):].strip()
            in_meta3 = False
    else:
        body_lines3.append(l)

meta3['meta_title'] = meta3['title'] + " | Printex"
meta3['meta_description'] = "If you run a business, sooner or later you will need printed T-shirts. Compare screen printing, DTF, DTG, heat transfer, sublimation, vinyl, and embroidery."

def build_article_html(lines):
    html_out = []
    in_ul = False
    
    i = 0
    while i < len(lines):
        line = lines[i].strip()
        if not line:
            if in_ul:
                html_out.append('</ul>')
                in_ul = False
            i += 1
            continue
            
        if line.startswith('H2: '):
            if in_ul: html_out.append('</ul>'); in_ul = False
            html_out.append(f'<h2>{line[4:].strip()}</h2>')
            i += 1
            continue
            
        if line.startswith('H3: '):
            if in_ul: html_out.append('</ul>'); in_ul = False
            html_out.append(f'<h3>{line[4:].strip()}</h3>')
            i += 1
            continue
            
        if line.startswith('Table Columns:'):
            if in_ul: html_out.append('</ul>'); in_ul = False
            cols = [c.strip() for c in line[len('Table Columns:'):].split('|')]
            html_out.append('<div style="overflow-x:auto; margin:1.5rem 0;"><table class="compare-table" style="width:100%;">')
            html_out.append('<thead><tr>' + ''.join(f'<th style="padding:0.75rem 1rem; color:var(--primary-gold);">{c}</th>' for c in cols) + '</tr></thead>')
            html_out.append('<tbody>')
            i += 1
            while i < len(lines) and lines[i].strip().startswith('- '):
                row_cells = [c.strip() for c in lines[i].strip()[2:].split('|')]
                html_out.append('<tr>' + ''.join(f'<td style="padding:0.75rem 1rem;">{c}</td>' for c in row_cells) + '</tr>')
                i += 1
            html_out.append('</tbody></table></div>')
            continue

        if line.startswith('- '):
            if not in_ul:
                html_out.append('<ul>')
                in_ul = True
            html_out.append(f'<li>{line[2:].strip()}</li>')
            i += 1
            continue
            
        m_num = re.match(r'^(\d+)\.\s+(.*)$', line)
        if m_num:
            if in_ul: html_out.append('</ul>'); in_ul = False
            num = m_num.group(1)
            rest = m_num.group(2)
            
            # Check if next line is an answer (indented or A: )
            if i + 1 < len(lines) and (lines[i+1].startswith('   ') or lines[i+1].startswith('A: ')):
                ans = lines[i+1].strip()
                html_out.append(f'<div style="margin-bottom:1.5rem;">')
                html_out.append(f'<h4 style="color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;">{num}. {rest}</h4>')
                html_out.append(f'<p style="margin:0; color:var(--text-secondary); line-height:1.7;">{ans}</p>')
                html_out.append(f'</div>')
                i += 2
                continue
            else:
                html_out.append(f'<p><strong>{num}.</strong> {rest}</p>')
                i += 1
                continue
                
        if in_ul: html_out.append('</ul>'); in_ul = False
        html_out.append(f'<p>{line}</p>')
        i += 1
        
    if in_ul: html_out.append('</ul>')
    return '\n'.join(html_out)

articles_data = [
    {
        'id': 1,
        'title': meta1['title'],
        'slug': meta1['slug'],
        'seo_title': meta1['meta_title'],
        'seo_description': meta1['meta_description'],
        'excerpt': [l.strip() for l in body_lines1 if l.strip()][0],
        'content': build_article_html(body_lines1),
        'featured_image': 'assets/images/dtf_fabric_print.jpg',
        'author': 'OrderPrintex Editorial Team',
        'read_time': '6 min read',
        'category_id': 1,
        'status': 'published'
    },
    {
        'id': 2,
        'title': meta2['title'],
        'slug': meta2['slug'],
        'seo_title': meta2['meta_title'],
        'seo_description': meta2['meta_description'],
        'excerpt': [l.strip() for l in body_lines2 if l.strip()][0],
        'content': build_article_html(body_lines2),
        'featured_image': 'assets/images/uv_dtf_drinkware.jpg',
        'author': 'OrderPrintex Editorial Team',
        'read_time': '5 min read',
        'category_id': 1,
        'status': 'published'
    },
    {
        'id': 3,
        'title': meta3['title'],
        'slug': meta3['slug'],
        'seo_title': meta3['meta_title'],
        'seo_description': meta3['meta_description'],
        'excerpt': [l.strip() for l in body_lines3 if l.strip()][0],
        'content': build_article_html(body_lines3),
        'featured_image': 'assets/images/custom_tshirt_apparel.jpg',
        'author': meta3['author'],
        'read_time': '8 min read',
        'category_id': 2,
        'status': 'published'
    }
]

with open(r'c:\Movies\Internship\OrderPrintex\scratch\articles_payload.json', 'w', encoding='utf-8') as f:
    json.dump(articles_data, f, ensure_ascii=False, indent=2)

print("Saved articles_payload.json successfully!")
for a in articles_data:
    print(f"- ID {a['id']}: {a['title']} ({a['slug']}) - content len: {len(a['content'])}")
