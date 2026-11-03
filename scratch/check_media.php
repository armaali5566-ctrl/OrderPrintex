<?php
$f = file('C:/Users/Arman/.gemini/antigravity/brain/0f535ace-a0ab-44e0-a690-2da68588e47f/.system_generated/logs/transcript.jsonl');
foreach ($f as $line) {
    $data = json_decode($line, true);
    if (!empty($data['media'])) {
        echo "Found media in step " . $data['step_index'] . ": " . json_encode($data['media']) . "\n";
    }
}
echo "Done checking transcript.\n";

