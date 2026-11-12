import json

log_path = r"C:\Users\Arman\.gemini\antigravity\brain\0f535ace-a0ab-44e0-a690-2da68588e47f\.system_generated\logs\transcript_full.jsonl"

with open(log_path, 'r', encoding='utf-8') as f:
    for line_idx, line in enumerate(f):
        data = json.loads(line)
        if data.get('type') == 'USER_INPUT':
            content = data.get('content', '')
            if 'PART 2: T-SHIRT PRINTING' in content or 'PART 2:' in content:
                print(f"Found on line {line_idx}")
                # Save this content to a separate text file so we can read it easily
                with open(r"c:\Movies\Internship\OrderPrintex\scratch\part2_prompt.txt", 'w', encoding='utf-8') as out:
                    out.write(content)
                print("Written to scratch/part2_prompt.txt")
                break

