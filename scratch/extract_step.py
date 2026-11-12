import json

transcript_path = r"C:\Users\Arman\.gemini\antigravity\brain\0f535ace-a0ab-44e0-a690-2da68588e47f\.system_generated\logs\transcript_full.jsonl"
output_path = r"c:\Movies\Internship\OrderPrintex\scratch\extracted_prompt.txt"

with open(transcript_path, "r", encoding="utf-8") as f:
    for line in f:
        data = json.loads(line)
        if data.get("step_index") == 8969:
            content = data.get("content", "")
            with open(output_path, "w", encoding="utf-8") as out:
                out.write(content)
            print("Wrote extracted prompt! Length:", len(content))
            break

