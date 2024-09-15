<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتایج جستجو برای "{{search_query}}" | مشکی</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="pagination.css">
</head>
<body>
    <header>
        <nav>
            <!-- منو را از قالب اصلی کپی کنید -->
        </nav>
        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="q" placeholder="جستجوی آهنگ یا هنرمند" value="{{search_query}}" required>
            <button type="submit">جستجو</button>
        </form>
    </header>

    <main>
        <h1>نتایج جستجو برای "{{search_query}}"</h1>
        <div class="search-results">
            {{search_results}}
        </div>
        {{pagination}}
    </main>

    <footer>
        <!-- فوتر را از قالب اصلی کپی کنید -->
    </footer>

    <script>
        function togglePlayer(button) {
            var player = button.nextElementSibling;
            if (player.style.display === "none") {
                player.style.display = "block";
                button.textContent = "نمایش کمتر";
            } else {
                player.style.display = "none";
                button.textContent = "نمایش بیشتر";
            }
        }
    </script>
</body>
</html>
