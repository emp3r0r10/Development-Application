<?php
header('Content-Type: application/json');

// ===== LAB CONFIG =====
$LAB = [
    "allow_user_system_prompt" => true,     // toggle prompt injection
    "include_sensitive_context" => true,    // toggle secret leakage
    "allow_file_context" => false,          // toggle file injection
    "allow_raw_model_output_execution" => false // toggle dangerous code execution
];

// ===== Fake sensitive data =====
$FAKE_SECRETS = "API_KEY=FAKE-1234\nPASSWORD=lab_password";

// ===== Receive user input =====
$userMessage = $_POST['message'] ?? '';
$userSystem = $_POST['system'] ?? '';
$includeFiles = isset($_POST['includeFiles']) && $_POST['includeFiles'] == "true";

// ===== Build system prompt =====
$systemPrompt = "You are a helpful assistant.";
if ($LAB["allow_user_system_prompt"] && !empty($userSystem)) {
    // Vulnerability: let the user override system instructions
    $systemPrompt = $userSystem;
}

// ===== Build context =====
$context = "Conversation history here...";
if ($LAB["include_sensitive_context"]) {
    // Vulnerability: secrets in context
    $context .= "\n" . $FAKE_SECRETS;
}
if ($LAB["allow_file_context"] && $includeFiles) {
    // Vulnerability: arbitrary file content in context
    $context .= "\n[FILE: user_uploaded_document_contents_here]";
}

// ===== Fake LLM reply =====
function fakeLLMReply($system, $user, $context, $LAB) {
    $reply = "User asked: \"$user\"\n";
    $reply .= "System prompt in use: \"$system\"\n";

    if ($LAB["include_sensitive_context"]) {
        $reply .= "\n[🔴 Data Leakage Vulnerability Enabled]\n";
        $reply .= "Context contains sensitive info:\n$context\n";
    } else {
        $reply .= "\nContext length: " . strlen($context) . "\n";
    }

    if ($LAB["allow_file_context"] && strpos($context, "[FILE:") !== false) {
        $reply .= "\n[🟠 File Injection Vulnerability Enabled]\n";
        $reply .= "File content was added to the prompt.\n";
    }

    return $reply;
}

$modelReply = fakeLLMReply($systemPrompt, $userMessage, $context, $LAB);

if ($LAB["allow_raw_model_output_execution"]) {
    $modelReply .= "\n[⚠ Raw Model Output Execution Vulnerability Enabled]";
}

// ===== Return JSON response =====
echo json_encode([
    "reply" => nl2br($modelReply)
]);
