<!-- Search Form -->
<div class="search-container">
    <form action="search.php" method="GET" id="searchForm">
        <div class="search-input-group">
            <input type="text" name="query" id="searchInput" 
                   placeholder="Search users by name or email..." 
                   value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
            <button type="submit">Search</button>
        </div>
    </form>
    <div id="liveSearchResults" class="live-results"></div>
</div>
