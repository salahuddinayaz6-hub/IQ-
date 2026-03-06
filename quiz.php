<?php
session_start();
require_once 'db_config.php';
 
// Initialize session variables if not set
if (!isset($_SESSION['current_question_index'])) {
    $_SESSION['current_question_index'] = 0;
    $_SESSION['answers'] = [];
}
 
// Fetch questions
try {
    $stmt = $pdo->query("SELECT * FROM questions ORDER BY id ASC");
    $questions = $stmt->fetchAll();
} catch (Exception $e) {
    // Fallback if DB not ready
    $questions = [
        [
            'id' => 1,
            'question_text' => 'Which number should come next in the series? 2, 4, 8, 16, 32, ...',
            'option_a' => '48', 'option_b' => '64', 'option_c' => '54', 'option_d' => '72',
            'correct_option' => 'B'
        ]
    ];
}
 
$total_questions = count($questions);
$current_index = $_SESSION['current_question_index'];
 
// Check if test is finished
if ($current_index >= $total_questions) {
    echo "<script>window.location.href = 'results.php';</script>";
    exit;
}
 
$current_question = $questions[$current_index];
$progress = (($current_index) / $total_questions) * 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz | MindMatrix IQ Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --accent: #ec4899;
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.8);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
        }
 
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }
 
        body {
            background-color: var(--bg);
            background-image: radial-gradient(circle at center, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
 
        .container {
            width: 100%;
            max-width: 800px;
            animation: fadeIn 0.5s ease-out;
        }
 
        .quiz-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }
 
        .progress-container {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-bottom: 2.5rem;
            overflow: hidden;
        }
 
        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            transition: width 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
 
        .question-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
 
        .question-number {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
 
        .timer {
            font-variant-numeric: tabular-nums;
            color: var(--text-dim);
            font-weight: 500;
        }
 
        .question-text {
            font-size: 1.75rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 2.5rem;
        }
 
        .options-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
 
        .option-label {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.25rem 1.5rem;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
 
        .option-label:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: var(--primary);
            transform: translateX(5px);
        }
 
        .option-label.selected {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
        }
 
        .option-label input {
            display: none;
        }
 
        .option-indicator {
            width: 24px;
            height: 24px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin-right: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
 
        .option-label.selected .option-indicator {
            border-color: var(--primary);
            background: var(--primary);
        }
 
        .option-label.selected .option-indicator::after {
            content: '';
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }
 
        .option-text {
            font-size: 1.1rem;
            font-weight: 500;
        }
 
        .actions {
            margin-top: 3rem;
            display: flex;
            justify-content: flex-end;
        }
 
        .next-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
 
        .next-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
 
        .next-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4);
        }
 
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
 
        @media (max-width: 640px) {
            .quiz-card { padding: 1.5rem; }
            .question-text { font-size: 1.25rem; }
        }
    </style>
</head>
<body>
 
    <div class="container">
        <div class="quiz-card">
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
            </div>
 
            <div class="question-meta">
                <span class="question-number">Question <?php echo ($current_index + 1); ?> of <?php echo $total_questions; ?></span>
            </div>
 
            <h2 class="question-text"><?php echo htmlspecialchars($current_question['question_text']); ?></h2>
 
            <form id="quizForm" method="POST" action="process_answer.php">
                <input type="hidden" name="question_id" value="<?php echo $current_question['id']; ?>">
 
                <div class="options-grid">
                    <label class="option-label" onclick="selectOption(this)">
                        <input type="radio" name="answer" value="A" required>
                        <div class="option-indicator"></div>
                        <span class="option-text"><?php echo htmlspecialchars($current_question['option_a']); ?></span>
                    </label>
 
                    <label class="option-label" onclick="selectOption(this)">
                        <input type="radio" name="answer" value="B">
                        <div class="option-indicator"></div>
                        <span class="option-text"><?php echo htmlspecialchars($current_question['option_b']); ?></span>
                    </label>
 
                    <label class="option-label" onclick="selectOption(this)">
                        <input type="radio" name="answer" value="C">
                        <div class="option-indicator"></div>
                        <span class="option-text"><?php echo htmlspecialchars($current_question['option_c']); ?></span>
                    </label>
 
                    <label class="option-label" onclick="selectOption(this)">
                        <input type="radio" name="answer" value="D">
                        <div class="option-indicator"></div>
                        <span class="option-text"><?php echo htmlspecialchars($current_question['option_d']); ?></span>
                    </label>
                </div>
 
                <div class="actions">
                    <button type="submit" class="next-btn" id="nextBtn" disabled>
                        <span>Next Question</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
 
    <script>
        function selectOption(el) {
            // Remove selected class from others
            document.querySelectorAll('.option-label').forEach(label => {
                label.classList.remove('selected');
            });
            // Add to current
            el.classList.add('selected');
            // Enable next button
            document.getElementById('nextBtn').disabled = false;
        }
 
        document.getElementById('quizForm').onsubmit = function(e) {
            // Standard form submission allowed to show the intermediate 'Analyzing' screen
            document.getElementById('nextBtn').innerHTML = 'Processing...';
        };
    </script>
</body>
</html>
 
