<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book 22</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #eef2f3, #dfe9f3);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        img {
            max-width: 100%;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .back-btn {
            display: inline-block;
            margin-top: auto; /* pushes button to the bottom of container */
            padding: 12px 24px;
            background-color: #16a8d9;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
            margin-bottom: 15px; /* small space from bottom */
        }

        .back-btn:hover {
            background-color: #128bb3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Diary Of A Wimpy Kid</h1>
        <img src="images/diary of a wimpy kid front page.jpg" alt="Book back cover">
        <img src="images/diary of a wimpy kid back page.jpg" alt="Book front cover">

        <a href="http://localhost/books/public/book_view.php" class="back-btn">Go Back to Book View</a>
    </div>
</body>
</html>