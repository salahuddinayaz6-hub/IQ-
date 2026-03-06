<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindMatrix | Professional IQ Assessment</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --accent: #ec4899;
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
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
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.15) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }
 
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -1;
            animation: animateBackground 100s linear infinite;
        }
 
        @keyframes animateBackground {
            from { background-position: 0 0; }
            to { background-position: 500px 500px; }
        }
 
        header {
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
 
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }
 
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
        }
 
        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
 
        .hero p {
            font-size: 1.25rem;
            color: var(--text-dim);
            max-width: 600px;
            margin-bottom: 3rem;
            line-height: 1.6;
        }
 
        .cta-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.2rem 3rem;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
 
        .cta-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 35px -10px rgba(99, 102, 241, 0.6);
        }
 
        .cta-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
 
        .cta-btn:hover::after {
            left: 100%;
        }
 
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
 
        .feature-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 2.5rem;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }
 
        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(99, 102, 241, 0.3);
        }
 
        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
            font-size: 1.5rem;
        }
 
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
 
        .feature-card p {
            color: var(--text-dim);
            line-height: 1.6;
        }
 
        .stats {
            padding: 4rem 2rem;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
 
        footer {
            padding: 2rem;
            text-align: center;
            color: var(--text-dim);
            font-size: 0.9rem;
            margin-top: auto;
        }
 
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
 
        .animate {
            animation: fadeIn 0.8s ease forwards;
        }
 
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
 
        @media (max-width: 768px) {
            .hero h1 { font-size: 3rem; }
            .features { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">MINDMATRIX</div>
    </header>
 
    <main>
        <section class="hero">
            <h1 class="animate">Discover Your True <br><span style="color: var(--primary)">Cognitive Potential</span></h1>
            <p class="animate delay-1">Take the most advanced scientific IQ assessment designed to measure your logical, numerical, and spatial intelligence with precision.</p>
            <button onclick="startTest()" class="cta-btn animate delay-2">Start Advanced Test</button>
        </section>
 
        <section class="features">
            <div class="feature-card animate delay-1">
                <div class="feature-icon">🧩</div>
                <h3>Logical Reasoning</h3>
                <p>Challenge your ability to identify complex patterns and follow structural rules in abstract sequences.</p>
            </div>
            <div class="feature-card animate delay-1" style="animation-delay: 0.3s">
                <div class="feature-icon">🔢</div>
                <h3>Numerical Ability</h3>
                <p>Assess your mathematical problem-solving skills and your talent for working with numerical series.</p>
            </div>
            <div class="feature-card animate delay-1" style="animation-delay: 0.4s">
                <div class="feature-icon">📐</div>
                <h3>Spatial Awareness</h3>
                <p>Test how well you can mentally rotate and manipulate 2D and 3D shapes and structures.</p>
            </div>
        </section>
    </main>
 
    <footer>
        &copy; 2026 MindMatrix IQ Labs. Professional Intelligence Assessment.
    </footer>
 
    <script>
        function startTest() {
            // Using JS for redirection as requested
            window.location.href = 'quiz.php';
        }
    </script>
</body>
</html>
 
