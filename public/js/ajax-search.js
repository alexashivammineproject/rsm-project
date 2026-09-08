// AJAX Live Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const headerSearchInput = document.getElementById('headerSearchInput');
    const searchResults = document.getElementById('searchResults');
    const headerSearchForm = document.getElementById('headerSearchForm');
    
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const mobileSearchResults = document.getElementById('mobileSearchResults');
    const mobileSearchForm = document.getElementById('mobileSearchForm');

    // Debounce timer
    let searchTimer;

    // Function to show search results
    function showResults(results, resultsContainer) {
        if (!results || results.length === 0) {
            resultsContainer.innerHTML = '<div style="padding:10px; color:#999;">No results found</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        let html = '';
        results.forEach(product => {
            const image = product.alt_img ? '/storage/images/' + product.alt_img : '/storage/' + product.image;
            html += `
                <a href="/product/viewdetail/${product.slug}" style="display:flex; padding:8px 10px; border-bottom:1px solid #f0f0f0; text-decoration:none; color:#333; transition:background 0.2s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='white'">
                    <img src="${image}" alt="${product.title}" style="width:40px; height:40px; object-fit:cover; margin-right:10px; border-radius:3px;">
                    <div style="flex:1;">
                        <div style="font-weight:500; font-size:13px;">${product.title}</div>
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

    // Header Search Input Event
    if (headerSearchInput) {
        headerSearchInput.addEventListener('keyup', function() {
            const query = this.value.trim();

            clearTimeout(searchTimer);

            if (query.length < 2) {
                hideResults(searchResults);
                return;
            }

            searchTimer = setTimeout(() => {
                fetch(`/ajax/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => showResults(data, searchResults))
                    .catch(error => console.error('Search error:', error));
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search') || e.target === headerSearchInput) {
                if (e.target !== headerSearchInput) {
                    hideResults(searchResults);
                }
            }
        });
    }

    // Mobile Search Input Event
    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('keyup', function() {
            const query = this.value.trim();

            clearTimeout(searchTimer);

            if (query.length < 2) {
                hideResults(mobileSearchResults);
                return;
            }

            searchTimer = setTimeout(() => {
                fetch(`/ajax/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => showResults(data, mobileSearchResults))
                    .catch(error => console.error('Search error:', error));
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search') || e.target === mobileSearchInput) {
                if (e.target !== mobileSearchInput) {
                    hideResults(mobileSearchResults);
                }
            }
        });
    }
});
