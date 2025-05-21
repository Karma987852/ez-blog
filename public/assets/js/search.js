document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('#search');
    const searchInput = searchForm.querySelector('input[name="q"]');
    const searchResults = document.querySelector('#search-results');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length === 0) {
            searchResults.style.display = 'none';
            return;
        }
        searchTimeout = setTimeout(() => {
            fetch(`/ez-blog/src/controllers/search-api.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    
                    if (Array.isArray(data)) {
                        html += '<div class="search-section"><h4>Posts</h4>';
                        data.forEach(post => {
                            html += `<a href="/ez-blog/post?id=${post.id}">${post.title}</a>`;
                        });
                        html += '</div>';
                    } else if (data.posts && data.posts.length > 0) {
                        html += '<div class="search-section"><h4>Posts</h4>';
                        data.posts.forEach(post => {
                            html += `<a href="/ez-blog/post?id=${post.id}">${post.title}</a>`;
                        });
                        html += '</div>';
                    }

                    if (data.users && data.users.length > 0) {
                        html += '<div class="search-section"><h4>Users</h4>';
                        data.users.forEach(user => {
                            html += `<a href="/profile?id=${user.id}">${user.username}</a>`;
                        });
                        html += '</div>';
                    }

                    if (html === '') {
                        html = '<div class="no-results">No results found</div>';
                    }

                    searchResults.innerHTML = html;
                    searchResults.style.display = 'block';
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.style.display = 'none';
                });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});