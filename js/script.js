// Live Search Functionality
$(document).ready(function() {
    const searchInput = $('#searchInput');
    const liveResults = $('#liveSearchResults');
    let searchTimeout;

    // Live search on input
    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Hide results if query is empty
        if (query.length === 0) {
            liveResults.hide().empty();
            return;
        }

        // Set new timeout to avoid too many requests
        searchTimeout = setTimeout(() => {
            performLiveSearch(query);
        }, 300);
    });

    // Hide results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            liveResults.hide().empty();
        }
    });

    function performLiveSearch(query) {
        if (query.length < 2) {
            liveResults.hide().empty();
            return;
        }

        $.ajax({
            url: 'includes/live_search.php',
            type: 'GET',
            data: { query: query },
            dataType: 'json',
            success: function(response) {
                displayLiveResults(response);
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                liveResults.hide().empty();
            }
        });
    }

    function displayLiveResults(users) {
        liveResults.empty();
        
        if (users.length === 0) {
            liveResults.hide();
            return;
        }

        const resultsList = $('<div class="live-results-list"></div>');
        
        users.forEach(user => {
            const userItem = $(`
                <a href="profile.php?user_id=${user.id}" class="live-result-item">
                    <div class="live-user-avatar">
                        <img src="uploads/${user.profile_picture}" alt="${user.full_name}" onerror="this.src='uploads/default.png'">
                    </div>
                    <div class="live-user-info">
                        <strong>${escapeHtml(user.full_name)}</strong>
                        <small>${escapeHtml(user.email)}</small>
                    </div>
                </a>
            `);
            resultsList.append(userItem);
        });

        liveResults.html(resultsList).show();
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
