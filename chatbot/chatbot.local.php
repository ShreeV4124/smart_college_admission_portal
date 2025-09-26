<?php
require_once '../db_connect.php';
require_once '../db_functions.php';

$conn = connectDB(); // Must return PDO instance


$usermessage = trim($_POST['message'] ?? '');

function askGemini($usermessage) {
    $apiKey = "AIzaSyAqP7W66Ep652FzQvGAVxwUrSGbcaPuWKA"; 
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent";

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => "You are an AI assistant for a Students Admission Portal.
Only answer questions related to this system:
- Students register and choose a preferred course
- Admin verifies and assigns courses
- Student can edit info, view courses, and pay fees
- After successful payment, admission is confirmed

If question is unrelated (e.g., cooking, weather), reply with: 'I am an AI assistant for a Students Admission Portal!'.
You can reply to greetings like hello/thanks. If question is related to the student admission process, then you can reply by your own knowledge too.

Now respond to: $usermessage" ?? 'Hello!']
                ]
            ]
        ]
    ];

    $payload = json_encode($data);

    $ch = curl_init("$url?key=$apiKey");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    // Temp SSL fix
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    curl_close($ch);

   $result = json_decode($response, true);

// echo json_encode($result);
return json_encode($result);


}


if (!empty($usermessage)) {
    $like = "%$usermessage%";

    // Search in DB
    $row = getVal("
    SELECT * FROM chat_knowledge 
    WHERE keyword LIKE ? 
    ORDER BY count DESC, last_used DESC 
    LIMIT 1
", [$like]);

    if ($row) {
        // Found in DB
        $id = $row['id'];
        $answer = $row['answer'];

        // Update count & last_used
        $update = $conn->prepare("UPDATE chat_knowledge SET count = count + 1, last_used = NOW() WHERE id = ?");
        $update->execute([$id]);

        echo json_encode(["candidates" => [
        [
            "content" => [
                "parts" => [
                    ["text" => $answer]
                ]
            ]
        ]
    ]
]);
        exit;
    } else {
        // Not found → Ask Gemini
        $botReply = askGemini($usermessage);

        $decodedReply = json_decode($botReply, true);
        // Save new Q&A in DB
        $resp = $decodedReply["candidates"][0]["content"]["parts"][0]["text"];
        $insert = $conn->prepare("
            INSERT INTO chat_knowledge (question, keyword, answer)
            VALUES (?, ?, ?)
        ");
        $insert->execute([$usermessage, $usermessage, $resp]);

        echo $botReply;
        exit;
    }
}

?>
