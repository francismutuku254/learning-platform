<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "learning");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode([]);
    exit();
}

// Get quizzes
$quizzes_res = $mysqli->query("SELECT * FROM quizzes ORDER BY id DESC");
$quizzes = [];
while ($quiz = $quizzes_res->fetch_assoc()) {
    $quiz_id = $quiz['id'];

    $questions_res = $mysqli->prepare("SELECT * FROM quiz_questions WHERE quiz_id = ?");
    $questions_res->bind_param("i", $quiz_id);
    $questions_res->execute();
    $questions_result = $questions_res->get_result();

    $questions = [];
    while ($q = $questions_result->fetch_assoc()) {
        $questions[] = [
            'id' => (int)$q['id'],
            'question' => $q['question'],
            'option_a' => $q['option_a'],
            'option_b' => $q['option_b'],
            'option_c' => $q['option_c'],
            'option_d' => $q['option_d'],
            'correct_option' => $q['correct_option'],
        ];
    }
    $questions_res->close();

    $quizzes[] = [
        'id' => (int)$quiz['id'],
        'title' => $quiz['title'],
        'questions' => $questions,
    ];
}

echo json_encode($quizzes);
?>
