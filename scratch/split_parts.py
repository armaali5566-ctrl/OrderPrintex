with open(r"c:\Movies\Internship\OrderPrintex\scratch\part2_prompt.txt", 'r', encoding='utf-8') as f:
    text = f.read()

p2_idx = text.find("PART 2:")
p3_idx = text.find("PART 3:")

print("P2 index:", p2_idx)
print("P3 index:", p3_idx)

if p2_idx != -1 and p3_idx != -1:
    with open(r"c:\Movies\Internship\OrderPrintex\scratch\part2_only.txt", 'w', encoding='utf-8') as f2:
        f2.write(text[p2_idx:p3_idx])
    with open(r"c:\Movies\Internship\OrderPrintex\scratch\part3_only.txt", 'w', encoding='utf-8') as f3:
        f3.write(text[p3_idx:])
    print("Saved part2_only.txt and part3_only.txt")

