<?php
session_start();
require_once 'db_config.php';
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyzing...</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: 'Outfit', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #6366f1;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }
        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .text {
            margin-top: 20px;
            font-size: 1.2rem;
            letter-spacing: 1px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="loader"></div>
    <div class="text">ANALYZING RESPONSE...</div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
        $question_id = $_POST['question_id'];
        $user_answer = $_POST['answer'];
        $_SESSION['answers'][$question_id] = $user_answer;
        $_SESSION['current_question_index']++;
 
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM questions");
            $total = $stmt->fetchColumn();
        } catch (Exception $e) { $total = 10; }
 
        $redirect_url = ($_SESSION['current_question_index'] >= $total) ? 'results.php' : 'quiz.php';
        echo "<script>
            setTimeout(function() {
                window.location.href = '$redirect_url';
            }, 800);
        </script>";
    } else {
        echo "<script>window.location.href = 'index.php';</script>";
    }
    ?>
</body>
</html>
