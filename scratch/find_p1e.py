with open(r"c:\Movies\Internship\OrderPrintex\scratch\part2_prompt.txt", 'r', encoding='utf-8') as f:
    text = f.read()

p1e_idx = text.find("E. Blog Article Detail UI")
if p1e_idx == -1:
    p1e_idx = text.find("E. Blog")

if p1e_idx != -1:
    print(text[p1e_idx:p1e_idx+1500])
else:
    print("Not found directly, searching for Blog...")
    lines = text.split("\n")
    for i, l in enumerate(lines):
        if "blog" in l.lower() and "part 1" in l.lower() or "blog-details" in l.lower():
            print(f"Line {i}: {l}")

