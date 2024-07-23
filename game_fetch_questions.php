<?php
include 'conn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$level_id = isset($_GET['level_id']) ? intval($_GET['level_id']) : 0;

$questions = array();

if ($level_id > 0) {
    $sql = "SELECT Question, Option_A, Option_B, Option_C, Answer FROM question WHERE Level_ID = ?";
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        error_log('Prepare failed: ' . htmlspecialchars($con->error));
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }

    $stmt->bind_param('i', $level_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        error_log('Fetched Row: ' . print_r($row, true)); // Log the raw row data
        $questions[] = array(
            'question' => $row['Question'],
            'options' => array($row['Option_A'], $row['Option_B'], $row['Option_C']),
            'correct' => $row['Answer']
        );
    }

    $stmt->close();
    $con->close();
} else {
    error_log('Invalid level_id: ' . $level_id);
}

header('Content-Type: application/json');
echo json_encode($questions);

// Debugging output to see the raw questions array
error_log('Questions fetched: ' . print_r($questions, true));
?>
