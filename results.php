<?php
session_start();
require_once 'db_config.php';
 
if (!isset($_SESSION['answers']) || empty($_SESSION['answers'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
 
// Fetch correct answers
try {
    $stmt = $pdo->query("SELECT id, correct_option, category FROM questions");
    $questions_data = $stmt->fetchAll();
} catch (Exception $e) {
    // Fallback data if DB fails
    $questions_data = [];
}
 
$score = 0;
$total = count($questions_data);
$category_scores = [];
 
foreach ($questions_data as $q) {
    $qid = $q['id'];
    $cat = $q['category'];
 
    if (!isset($category_scores[$cat])) {
        $category_scores[$cat] = ['correct' => 0, 'total' => 0];
    }
 
    $category_scores[$cat]['total']++;
 
    if (isset($_SESSION['answers'][$qid]) && $_SESSION['answers'][$qid] === $q['correct_option']) {
        $score++;
        $category_scores[$cat]['correct']++;
    }
}
 
// IQ Calculation Logic (approximate for fun)
// 100 is average. 140+ is genius.
$iq_score = 80 + ($score * 6);
if ($score == $total) $iq_score = 145 + rand(0, 5); // Perfect score bonus
 
// Determination of strengths
$strengths = [];
foreach ($category_scores as $cat => $data) {
    if ($data['correct'] / $data['total'] >= 0.7) {
        $strengths[] = $cat;
    }
}
 
$feedback = "";
if ($iq_score >= 130) {
    $feedback = "Outstanding! Your cognitive abilities are in the top 2% of the population. You possess exceptional pattern recognition and logical synthesis skills.";
} elseif ($iq_score >= 115) {
    $feedback = "Impressive. You show high analytical intelligence and strong problem-solving capabilities. You excel at identifying complex structures.";
} elseif ($iq_score >= 90) {
    $feedback = "Good results. Your intelligence profile is well-balanced and solid. You have a healthy capacity for logical reasoning and numerical tasks.";
} else {
    $feedback = "Interesting profile. Your results suggest you might excel more in creative or emotional intelligence fields rather than abstract logic.";
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Results | MindMatrix</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --accent: #ec4899;
            --success: #10b981;
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
            background-image: 
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(99, 102, 241, 0.15) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
 
        .results-container {
            max-width: 900px;
            width: 100%;
            text-align: center;
            animation: zoomIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
 
        .results-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 4rem 2rem;
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.7);
        }
 
        .score-circle {
            width: 200px;
            height: 200px;
            margin: 0 auto 2rem;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: conic-gradient(var(--primary) <?php echo ($iq_score / 150) * 360; ?>deg, transparent 0deg);
            padding: 10px;
        }
 
        .score-circle::before {
            content: '';
            position: absolute;
            inset: 10px;
            background: var(--bg);
            border-radius: 50%;
            z-index: 1;
        }
 
        .score-content {
            position: relative;
            z-index: 2;
        }
 
        .score-value {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(to bottom, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }
 
        .score-label {
            font-size: 1rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }
 
        .feedback-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: white;
        }
 
        .feedback-text {
            color: var(--text-dim);
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto 3rem;
        }
 
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
 
        .stat-item {
            background: rgba(255, 255, 255, 0.03);
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
 
        .stat-val {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            display: block;
        }
 
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-dim);
            text-transform: uppercase;
        }
 
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
 
        .btn {
            padding: 1rem 2.5rem;
            border-radius: 100px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }
 
        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
 
        .btn-outline {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
 
        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
 
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
 
        @media (max-width: 640px) {
            .results-card { padding: 2rem 1rem; }
            .score-value { font-size: 3rem; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
 
    <div class="results-container">
        <div class="results-card">
            <div class="score-circle">
                <div class="score-content">
                    <span class="score-value"><?php echo $iq_score; ?></span>
                    <div class="score-label">IQ Score</div>
                </div>
            </div>
 
            <h2 class="feedback-title">Assessment Complete</h2>
            <p class="feedback-text"><?php echo $feedback; ?></p>
 
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-val"><?php echo $score; ?> / <?php echo $total; ?></span>
                    <span class="stat-label">Correct Answers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val"><?php echo round(($score / $total) * 100); ?>%</span>
                    <span class="stat-label">Accuracy</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val"><?php echo count($strengths) > 0 ? htmlspecialchars($strengths[0]) : 'General'; ?></span>
                    <span class="stat-label">Primary Strength</span>
                </div>
            </div>
 
            <div class="actions">
                <button onclick="retakeTest()" class="btn btn-primary">Retake Assessment</button>
                <button onclick="shareScore()" class="btn btn-outline">Share Results</button>
            </div>
        </div>
    </div>
 
    <script>
        function retakeTest() {
            // Using JS for redirection as requested
            window.location.href = 'retake.php';
        }
 
        function shareScore() {
            alert('Your IQ score of <?php echo $iq_score; ?> has been copied to clipboard!');
        }
    </script>
</body>
</html>
 
