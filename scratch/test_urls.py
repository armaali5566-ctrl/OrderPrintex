import urllib.request

urls = [
    'http://127.0.0.1:8080/services.php',
    'http://127.0.0.1:8080/services/dtf-printing.php',
    'http://127.0.0.1:8080/services/uv-dtf-printing.php',
    'http://127.0.0.1:8080/services/corporate-printing.php',
    'http://127.0.0.1:8080/products/',
    'http://127.0.0.1:8080/gallery.php',
    'http://127.0.0.1:8080/blog.php',
    'http://127.0.0.1:8080/contact.php',
    'http://127.0.0.1:8080/get-quote.php',
    'http://127.0.0.1:8080/blog/dtf-printing-technology-custom-apparel/',
    'http://127.0.0.1:8080/blog/uv-dtf-printing-product-branding/',
    'http://127.0.0.1:8080/blog/different-t-shirt-printing-techniques/'
]

for u in urls:
    try:
        req = urllib.request.Request(u, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=5) as res:
            print(f"{res.status} OK: {u}")
    except Exception as e:
        print(f"FAILED: {u} - {e}")

