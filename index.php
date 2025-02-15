<?php
// Start session to maintain score
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizCraft - PHP Quiz</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .question {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .question h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        .options {
            margin-left: 20px;
        }
        .option-item {
            margin: 10px 0;
        }
        .result {
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .correct {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .incorrect {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto;
        }
        button:hover {
            background-color: #2980b9;
        }
        .score-display {
            text-align: center;
            font-size: 24px;
            color: #2c3e50;
            margin: 20px 0;
        }
        .grade {
            font-size: 18px;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 QuizCraft PHP Challenge</h1>
        
        <?php
        // Define quiz questions and answers
        $questions = [
            1 => [
                'question' => 'What does PHP stand for?',
                'options' => [
                    'a' => 'Personal Home Page',
                    'b' => 'PHP: Hypertext Preprocessor',
                    'c' => 'Program High Processor',
                    'd' => 'Preprocessor Home Platform'
                ],
                'correct' => 'b'
            ],
            2 => [
                'question' => 'Which of the following is the correct way to start a PHP session?',
                'options' => [
                    'a' => '$_SESSION_START()',
                    'b' => 'session_start()',
                    'c' => 'start_session()',
                    'd' => 'init_session()'
                ],
                'correct' => 'b'
            ],
            3 => [
                'question' => 'Which symbol is used to concatenate strings in PHP?',
                'options' => [
                    'a' => '+',
                    'b' => '.',
                    'c' => '&',
                    'd' => ','
                ],
                'correct' => 'b'
            ],
            4 => [
                'question' => 'What is the correct way to create a function in PHP?',
                'options' => [
                    'a' => 'function myFunction()',
                    'b' => 'new function myFunction()',
                    'c' => 'create function myFunction()',
                    'd' => 'def myFunction()'
                ],
                'correct' => 'a'
            ],
            5 => [
                'question' => 'Which superglobal variable holds information about headers, paths, and script locations?',
                'options' => [
                    'a' => '$_GET',
                    'b' => '$_POST',
                    'c' => '$_SERVER',
                    'd' => '$_FILES'
                ],
                'correct' => 'c'
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $score = 0;
            $results = [];
            $totalQuestions = count($questions);
            
            // Check answers
            foreach ($questions as $qNum => $questionData) {
                if (isset($_POST["q$qNum"])) {
                    $userAnswer = $_POST["q$qNum"];
                    $isCorrect = ($userAnswer === $questionData['correct']);
                    if ($isCorrect) {
                        $score++;
                    }
                    $results[$qNum] = $isCorrect;
                }
            }

            // Calculate percentage
            $percentage = ($score / $totalQuestions) * 100;

            // Determine grade
            if ($percentage >= 90) {
                $grade = 'Outstanding! 🏆';
            } elseif ($percentage >= 80) {
                $grade = 'Excellent! 🌟';
            } elseif ($percentage >= 70) {
                $grade = 'Good Job! 👍';
            } elseif ($percentage >= 60) {
                $grade = 'Pass ✅';
            } else {
                $grade = 'Need Improvement 📚';
            }

            // Display results
            echo "<div class='result'>";
            echo "<h2>Quiz Results</h2>";
            echo "<div class='score-display'>Score: $score out of $totalQuestions</div>";
            echo "<div class='grade'>Grade: $grade ($percentage%)</div>";
            
            foreach ($questions as $qNum => $questionData) {
                $class = $results[$qNum] ? 'correct' : 'incorrect';
                echo "<div class='$class'>";
                echo "<p><strong>Question $qNum:</strong> " . ($results[$qNum] ? '✅ Correct!' : '❌ Incorrect');
                echo "<br>Your answer: " . $questionData['options'][$_POST["q$qNum"]];
                if (!$results[$qNum]) {
                    echo "<br>Correct answer: " . $questionData['options'][$questionData['correct']];
                }
                echo "</p></div>";
            }
            echo "</div>";
            echo "<button onclick='window.location.reload()'>Try Again</button>";
        } else {
            // Display quiz form
            echo "<form method='post' action=''>";
            foreach ($questions as $qNum => $questionData) {
                echo "<div class='question'>";
                echo "<h3>Question $qNum: {$questionData['question']}</h3>";
                echo "<div class='options'>";
                foreach ($questionData['options'] as $option => $text) {
                    echo "<div class='option-item'>";
                    echo "<input type='radio' id='q{$qNum}_{$option}' name='q$qNum' value='$option' required>";
                    echo "<label for='q{$qNum}_{$option}'> $text</label>";
                    echo "</div>";
                }
                echo "</div></div>";
            }
            echo "<button type='submit'>Submit Quiz</button>";
            echo "</form>";
        }
        ?>
    </div>
</body>
</html> 