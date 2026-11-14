import urllib.request
import json
import re
import sys

BASE_URL = "http://127.0.0.1:8080"

PAGES_TO_TEST = [
    {
        "name": "Homepage",
        "url": f"{BASE_URL}/",
        "expected_types": {"LocalBusiness", "WebSite", "ItemList", "Service", "Organization"}
    },
    {
        "name": "About Us",
        "url": f"{BASE_URL}/about",
        "expected_types": {"AboutPage", "BreadcrumbList", "LocalBusiness", "WebSite", "Service", "Organization"}
    },
    {
        "name": "Services Listing",
        "url": f"{BASE_URL}/services",
        "expected_types": {"CollectionPage", "ItemList", "BreadcrumbList", "LocalBusiness", "WebSite", "Service", "Organization"}
    },
    {
        "name": "DTF Printing (Service)",
        "url": f"{BASE_URL}/services/dtf-printing",
        "expected_types": {"Service", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "UV DTF Printing (Service)",
        "url": f"{BASE_URL}/services/uv-dtf-printing",
        "expected_types": {"Service", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "T-Shirt Printing (Service)",
        "url": f"{BASE_URL}/services/t-shirt-printing",
        "expected_types": {"Service", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Corporate Printing (Service)",
        "url": f"{BASE_URL}/services/corporate-printing",
        "expected_types": {"Service", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Products Listing",
        "url": f"{BASE_URL}/products",
        "expected_types": {"CollectionPage", "ItemList", "BreadcrumbList", "LocalBusiness", "WebSite", "Product", "Organization"}
    },
    {
        "name": "Individual Product (custom-dtf-gang-sheet)",
        "url": f"{BASE_URL}/products/custom-dtf-gang-sheet",
        "expected_types": {"Product", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Portfolio / Gallery",
        "url": f"{BASE_URL}/gallery",
        "expected_types": {"CollectionPage", "ImageGallery", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Blog Listing",
        "url": f"{BASE_URL}/blog",
        "expected_types": {"Blog", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Individual Blog Article (dtf-printing-technology-custom-apparel)",
        "url": f"{BASE_URL}/blog/dtf-printing-technology-custom-apparel",
        "expected_types": {"BlogPosting", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Contact Us",
        "url": f"{BASE_URL}/contact",
        "expected_types": {"ContactPage", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "FAQ",
        "url": f"{BASE_URL}/faq",
        "expected_types": {"FAQPage", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Pricing",
        "url": f"{BASE_URL}/pricing",
        "expected_types": {"WebPage", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    },
    {
        "name": "Get a Quote",
        "url": f"{BASE_URL}/get-quote",
        "expected_types": {"WebPage", "BreadcrumbList", "LocalBusiness", "WebSite", "Organization"}
    }
]

def fetch_and_validate():
    all_ok = True
    results = []

    print(f"{'Page':<35} | {'Status':<6} | {'Script Count':<12} | {'Detected Top-Level Types'}")
    print("-" * 105)

    for p in PAGES_TO_TEST:
        req = urllib.request.Request(p["url"], headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"})
        try:
            with urllib.request.urlopen(req, timeout=10) as resp:
                html = resp.read().decode('utf-8', errors='replace')
        except Exception as e:
            print(f"{p['name']:<35} | ERROR  | Could not fetch: {e}")
            all_ok = False
            continue

        # Find all <script type="application/ld+json"> blocks
        scripts = re.findall(r'<script\s+type=[\'"]application/ld\+json[\'"][^>]*>(.*?)</script>', html, re.DOTALL | re.IGNORECASE)
        
        detected_types = set()
        top_level_types = []
        errors = []

        for idx, s in enumerate(scripts):
            raw = s.strip()
            try:
                data = json.loads(raw)
                if "@graph" in data and isinstance(data["@graph"], list):
                    for node in data["@graph"]:
                        t = node.get("@type")
                        if isinstance(t, list):
                            for sub_t in t:
                                detected_types.add(sub_t)
                                top_level_types.append(sub_t)
                        elif t:
                            detected_types.add(t)
                            top_level_types.append(t)
                else:
                    t = data.get("@type")
                    if isinstance(t, list):
                        for sub_t in t:
                            detected_types.add(sub_t)
                            top_level_types.append(sub_t)
                    elif t:
                        detected_types.add(t)
                        top_level_types.append(t)
            except Exception as err:
                errors.append(f"Script #{idx} JSON parse error: {err}")

        expected = p["expected_types"]
        missing = expected - detected_types
        script_count = len(scripts)
        status = "PASS" if not missing and not errors and script_count == 1 else "FAIL"

        if status == "FAIL":
            all_ok = False

        types_summary = ", ".join(sorted(list(detected_types)))
        print(f"{p['name']:<35} | {status:<6} | {script_count:<12} | {types_summary}")
        if missing:
            print(f"   --> MISSING EXPECTED: {missing}")
        if errors:
            print(f"   --> PARSE ERRORS: {errors}")
        if script_count != 1:
            print(f"   --> SCRIPT COUNT WARNING: Found {script_count} scripts instead of exactly 1 unified script!")

        results.append({
            "name": p["name"],
            "url": p["url"],
            "status": status,
            "script_count": script_count,
            "expected": sorted(list(expected)),
            "detected": sorted(list(detected_types)),
            "top_level_types": top_level_types,
            "missing": sorted(list(missing)),
            "errors": errors
        })

    print("-" * 105)
    if all_ok:
        print(">>> ALL PAGES PERFECTLY PASSED SCHEMA VALIDATION! <<<")
    else:
        print(">>> SOME PAGES FAILED VALIDATION! Check output above. <<<")

    with open("scratch/validation_results.json", "w", encoding="utf-8") as f:
        json.dump(results, f, indent=2)

if __name__ == "__main__":
    fetch_and_validate()
