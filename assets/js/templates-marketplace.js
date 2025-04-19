document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const filterToggle = document.getElementById('filterToggle');
    const sortToggle = document.getElementById('sortToggle');
    const filtersSidebar = document.getElementById('filtersSidebar');
    const closeFilters = document.getElementById('closeFilters');
    const sortOverlay = document.getElementById('sortOverlay');
    const closeSortSheet = document.getElementById('closeSortSheet');
    const overlayBg = document.getElementById('overlayBg');
    const resetBtn = document.querySelector('.reset-btn');
    const applySortBtn = document.querySelector('.apply-sort-btn');
    const sortSelect = document.getElementById('sortSelect');
    const showMoreBtns = document.querySelectorAll('.show-more-btn');
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    const mobileFilterBar = document.querySelector('.mobile-filter-bar');
    
    // Make mobile filter bar fixed on small screens
    function updateMobileFilterPosition() {
        if (window.innerWidth <= 768) {
            mobileFilterBar.classList.add('fixed-bottom');
            document.body.classList.add('has-fixed-filter-bar');
        } else {
            mobileFilterBar.classList.remove('fixed-bottom');
            document.body.classList.remove('has-fixed-filter-bar');
        }
    }
    
    // Run on page load and whenever window is resized
    updateMobileFilterPosition();
    window.addEventListener('resize', updateMobileFilterPosition);
    
    // Open filters sidebar on mobile
    if (filterToggle) {
        filterToggle.addEventListener('click', function() {
            filtersSidebar.classList.add('active');
            overlayBg.classList.add('active');
            overlayBg.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    }
    
    // Close filters sidebar
    if (closeFilters) {
        closeFilters.addEventListener('click', function() {
            filtersSidebar.classList.remove('active');
            overlayBg.classList.remove('active');
            setTimeout(function() {
                overlayBg.style.display = 'none';
            }, 300);
            document.body.style.overflow = ''; // Re-enable scrolling
        });
    }
    
    // Open sort overlay
    if (sortToggle) {
        sortToggle.addEventListener('click', function() {
            sortOverlay.style.display = 'block';
            overlayBg.style.display = 'block';
            
            setTimeout(function() {
                sortOverlay.classList.add('active');
                overlayBg.classList.add('active');
            }, 10);
            
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    }
    
    // Close sort overlay
    if (closeSortSheet) {
        closeSortSheet.addEventListener('click', function() {
            closeSort();
        });
    }
    
    // Close sort function
    function closeSort() {
        sortOverlay.classList.remove('active');
        overlayBg.classList.remove('active');
        
        setTimeout(function() {
            sortOverlay.style.display = 'none';
            overlayBg.style.display = 'none';
        }, 300);
        
        document.body.style.overflow = ''; // Re-enable scrolling
    }
    
    // Close either overlay when clicking on background
    if (overlayBg) {
        overlayBg.addEventListener('click', function() {
            if (filtersSidebar.classList.contains('active')) {
                filtersSidebar.classList.remove('active');
            }
            
            if (sortOverlay.classList.contains('active')) {
                sortOverlay.classList.remove('active');
            }
            
            overlayBg.classList.remove('active');
            
            setTimeout(function() {
                overlayBg.style.display = 'none';
                
                if (sortOverlay.classList.contains('active')) {
                    sortOverlay.style.display = 'none';
                }
            }, 300);
            
            document.body.style.overflow = ''; // Re-enable scrolling
        });
    }
    
    // Reset filters
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            const form = document.getElementById('filtersForm');
            
            // Reset all checkboxes and radio buttons
            form.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(input => {
                if (input.name === 'category' || input.name === 'price_range') {
                    input.checked = input.value === '';
                } else {
                    input.checked = false;
                }
            });
            
            // Clear search input
            form.querySelector('input[name="search"]').value = '';
        });
    }
    
    // Apply sort
    if (applySortBtn) {
        applySortBtn.addEventListener('click', function() {
            const selectedSort = document.querySelector('input[name="mobile_sort"]:checked');
            
            if (selectedSort) {
                // Set the desktop sort select to match
                sortSelect.value = selectedSort.value;
                
                // Submit the form
                document.getElementById('filtersForm').submit();
            }
            
            closeSort();
        });
    }
    
    // Toggle Show More functionality
    if (showMoreBtns) {
        showMoreBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filterOptions = this.previousElementSibling;
                
                if (filterOptions.style.maxHeight) {
                    filterOptions.style.maxHeight = null;
                    this.textContent = 'Show more';
                } else {
                    filterOptions.style.maxHeight = filterOptions.scrollHeight + 'px';
                    this.textContent = 'Show less';
                }
            });
        });
    }
    
    // Wishlist buttons
    if (wishlistBtns) {
        wishlistBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    showToast('Added to wishlist');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    showToast('Removed from wishlist');
                }
            });
        });
    }
    
    // Add to cart buttons
    if (addToCartBtns) {
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const templateCard = this.closest('.template-card');
                const templateName = templateCard.querySelector('h3 a').textContent;
                
                showToast(`${templateName} added to cart`);
            });
        });
    }
    
    // Toast notification
    function showToast(message) {
        // Create toast element if it doesn't exist
        let toast = document.getElementById('toast-notification');
        
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast-notification';
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            toast.style.color = 'white';
            toast.style.padding = '12px 20px';
            toast.style.borderRadius = '4px';
            toast.style.zIndex = '9999';
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            document.body.appendChild(toast);
        }
        
        // Set toast message
        toast.textContent = message;
        
        // Show toast
        setTimeout(() => {
            toast.style.opacity = '1';
        }, 10);
        
        // Hide toast after delay
        setTimeout(() => {
            toast.style.opacity = '0';
        }, 3000);
    }
    
    // Make filter options match mobile sort when changed
    sortSelect.addEventListener('change', function() {
        const value = this.value;
        const mobileSort = document.querySelector(`input[name="mobile_sort"][value="${value}"]`);
        
        if (mobileSort) {
            mobileSort.checked = true;
        }
    });

    // Initialize form submission via select changes
    sortSelect.addEventListener('change', function() {
        document.getElementById('filtersForm').submit();
    });
}); 