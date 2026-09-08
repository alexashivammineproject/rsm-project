// AJAX Live Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const headerSearchInput = document.getElementById('headerSearchInput');
    const searchResults = document.getElementById('searchResults');
    const headerSearchForm = document.getElementById('headerSearchForm');
    
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const mobileSearchResults = document.getElementById('mobileSearchResults');

    let searchTimer;

    // Function to show search results
    function showResults(results, resultsContainer) {
        if (!results || results.length === 0) {
            resultsContainer.innerHTML = '<div style="padding:10px; color:#999; font-size:13px;">No results found</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        let html = '';
        results.forEach(product => {
            const image = product.alt_img ? '/storage/images/' + product.alt_img : '/storage/' + product.image;
            const title = product.title || 'Unknown Product';
            const slug = product.slug || '#';
            
            html += `
                <a href="/product/viewdetail/${slug}" 
                   style="display:flex; padding:10px; border-bottom:1px solid #f0f0f0; text-decoration:none; color:#333; transition:background 0.2s; align-items:center;" 
                   onmouseover="this.style.background='#f5f5f5'" 
                   onmouseout="this.style.background='white'">
                    <img src="${image}" alt="${title}" style="width:45px; height:45px; object-fit:cover; margin-right:12px; border-radius:3px; flex-shrink:0;">
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:500; font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${title}</div>
                    </div>
                </a>
            `;
        });
        resultsContainer.innerHTML = html;
        resultsContainer.style.display = 'block';
    }

    // Function to hide results
    function hideResults(resultsContainer) {
        resultsContainer.style.display = 'none';
    }

    // Function to perform search
    function performSearch(query, resultsContainer) {
        if (!query || query.length < 1) {
            hideResults(resultsContainer);
            return;
        }

        fetch(`/ajax/search?q=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => showResults(data, resultsContainer))
            .catch(error => {
                console.error('Search error:', error);
                hideResults(resultsContainer);
            });
    }

    // Header Search
    if (headerSearchInput && searchResults) {
        headerSearchInput.addEventListener('keyup', function() {
            const query = this.value.trim();
            clearTimeout(searchTimer);
            
            if (query.length === 0) {
                hideResults(searchResults);
                return;
            }

            searchTimer = setTimeout(() => {
                performSearch(query, searchResults);
            }, 250);
        });

        headerSearchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                performSearch(this.value.trim(), searchResults);
            }
        });

        // Hide on blur
        headerSearchInput.addEventListener('blur', function() {
            setTimeout(() => hideResults(searchResults), 200);
        });
    }

    // Mobile Search
    if (mobileSearchInput && mobileSearchResults) {
        mobileSearchInput.addEventListener('keyup', function() {
            const query = this.value.trim();
            clearTimeout(searchTimer);
            
            if (query.length === 0) {
                hideResults(mobileSearchResults);
                return;
            }

            searchTimer = setTimeout(() => {
                performSearch(query, mobileSearchResults);
            }, 250);
        });

        mobileSearchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                performSearch(this.value.trim(), mobileSearchResults);
            }
        });

        // Hide on blur
        mobileSearchInput.addEventListener('blur', function() {
            setTimeout(() => hideResults(mobileSearchResults), 200);
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search')) {
            if (searchResults) hideResults(searchResults);
            if (mobileSearchResults) hideResults(mobileSearchResults);
        }
    });
});
