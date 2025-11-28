<style>
    .search-panel {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 25px;
        margin-top: 50px;
        box-shadow: 0 0 20px rgba(136, 0, 255, 0.4);
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .search-panel h2 {
        color: #bb33ff;
        margin-bottom: 20px;
        text-shadow: 0px 0px 10px #bb33ff;
    }

    .search-panel label {
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 5px;
        color: #e5c3ff;
    }

    .search-input-dark {
        width: 100%;
        background: #111;
        border: 1px solid #444;
        padding: 8px;
        border-radius: 8px;
        color: #fff;
        outline: none;
    }

    .search-input-dark:focus {
        border-color: #bb33ff;
        box-shadow: 0 0 10px #bb33ff;
    }

    .search-check {
        margin-right: 10px;
    }

    .btn-search {
        margin-top: 20px;
        background: linear-gradient(90deg, #7300ff, #cc00ff);
        border: none;
        padding: 12px 28px;
        border-radius: 25px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 0 15px #8800ff;
    }

    .btn-search:hover {
        transform: scale(1.05);
        box-shadow: 0 0 25px #bb33ff;
    }

</style>

<div class="container">
    <div class="search-panel">
        <h2>🔍 Advanced Search</h2>

        <form action="index.php" method="GET">

            <input type="hidden" name="action" value="search">

            <!-- Keyword -->
            <label>Keyword</label>
            <input type="text" name="keyword" class="search-input-dark">

            <!-- Author -->
            <label>Author (username)</label>
            <input type="text" name="author" class="search-input-dark">

            <!-- Dates -->
            <label>Date From</label>
            <input type="text" name="date_from" class="search-input-dark" placeholder="YYYY-MM-DD">

            <label>Date To</label>
            <input type="text" name="date_to" class="search-input-dark" placeholder="YYYY-MM-DD">

            <!-- Min Likes -->
            <label>Minimum Likes</label>
            <input type="text" name="min_likes" class="search-input-dark">

            <!-- Checkboxes -->
            <div style="margin-top: 15px;">

                <label>
                    <input type="checkbox" name="has_image" value="1" class="search-check">
                    Has Image
                </label><br>

                <label>
                    <input type="checkbox" name="most_shared" value="1" class="search-check">
                    Most Shared
                </label>

            </div>

            <button type="submit" class="btn-search">SEARCH 🔍</button>

        </form>
    </div>
</div>
