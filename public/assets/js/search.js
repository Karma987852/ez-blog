document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm.querySelector('input[name="q"]');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length === 0) {
            searchResults.style.display = 'none';
            return;
        }

        // Add a small delay to prevent too many requests
        searchTimeout = setTimeout(() => {
            fetch(`/src/controllers/search-api.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const html = data.map(post => `
                            <div class="search-result-item">
                                <a href="/post?id=${post.id}">
                                    <h4>${post.title}</h4>
                                    <p class="post-meta">by ${post.username}</p>
                                </a>
                            </div>
                        `).join('');
                        
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="no-results">No results found</div>';
                        searchResults.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.style.display = 'none';
                });
        }, 300);
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchForm.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});