/**
 * Admin Panel JavaScript
 * Handles admin panel functionality and interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle profile dropdown
    const profileBtn = document.querySelector('.profile-btn');
    const profileMenu = document.querySelector('.profile-menu');
    
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function() {
            profileMenu.classList.toggle('active');
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!profileBtn.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove('active');
            }
        });
    }
    
    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('.toggle-sidebar');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Handle alerts
    const alertCloseButtons = document.querySelectorAll('.admin-alert-close');
    alertCloseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.closest('.admin-alert');
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.admin-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
    
    // Initialize DataTables if present
    const dataTables = document.querySelectorAll('.datatable');
    if (typeof $.fn.DataTable !== 'undefined' && dataTables.length > 0) {
        dataTables.forEach(table => {
            $(table).DataTable({
                responsive: true,
                pageLength: 25,
                ordering: true
            });
        });
    }
    
    // Bootstrap Modal Support
    // Handle data-toggle="modal" elements
    const modalTriggers = document.querySelectorAll('[data-toggle="modal"]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const modalElement = document.querySelector(targetId);
            
            if (modalElement) {
                // Show modal
                showModal(modalElement);
                
                // Add close event listeners to modal close buttons
                const closeButtons = modalElement.querySelectorAll('[data-dismiss="modal"]');
                closeButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        hideModal(modalElement);
                    });
                });
            }
        });
    });
    
    // Close modal when clicking outside the modal content
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal') && event.target.classList.contains('show')) {
            hideModal(event.target);
        }
    });
    
    // Helper functions for Bootstrap modals
    function showModal(modalElement) {
        // Create backdrop if it doesn't exist
        let backdrop = document.querySelector('.modal-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade';
            document.body.appendChild(backdrop);
            
            // Force reflow to ensure CSS transitions work properly
            void backdrop.offsetWidth;
            backdrop.classList.add('show');
        }
        
        // Show modal
        modalElement.style.display = 'block';
        document.body.classList.add('modal-open');
        
        // Force reflow to ensure CSS transitions work properly
        void modalElement.offsetWidth;
        
        modalElement.classList.add('show');
    }
    
    function hideModal(modalElement) {
        modalElement.classList.remove('show');
        
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.classList.remove('show');
        }
        
        // Remove modal and backdrop after transition
        setTimeout(function() {
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
            
            if (backdrop && backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        }, 300);
    }
    
    // Handle ESC key to close modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                hideModal(openModal);
            }
        }
    });
    
    // Handle bulk actions
    const bulkActionForms = document.querySelectorAll('.bulk-action-form');
    bulkActionForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedItems = form.querySelectorAll('input[name="items[]"]:checked');
            if (selectedItems.length === 0) {
                alert('Please select at least one item');
                return;
            }
            
            const action = form.querySelector('select[name="action"]').value;
            if (!action) {
                alert('Please select an action');
                return;
            }
            
            if (confirm('Are you sure you want to perform this action?')) {
                form.submit();
            }
        });
    });
    
    // Handle item deletion
    const deleteButtons = document.querySelectorAll('.delete-item');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this item?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.href;
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'action';
                input.value = 'delete';
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
    // Handle image preview
    const imageInputs = document.querySelectorAll('.image-upload');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.querySelector(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    
    // Handle slug generation
    const slugSources = document.querySelectorAll('[data-slug-source]');
    slugSources.forEach(source => {
        source.addEventListener('input', function() {
            const target = document.querySelector(this.dataset.slugTarget);
            if (target) {
                target.value = generateSlug(this.value);
            }
        });
    });
    
    // Handle dynamic form fields
    const addFieldButtons = document.querySelectorAll('.add-field');
    addFieldButtons.forEach(button => {
        button.addEventListener('click', function() {
            const template = document.querySelector(this.dataset.template);
            const container = document.querySelector(this.dataset.container);
            
            if (template && container) {
                const clone = template.content.cloneNode(true);
                container.appendChild(clone);
                
                // Initialize any special inputs in the clone
                initializeSpecialInputs(container.lastElementChild);
            }
        });
    });
    
    // Handle sortable lists
    const sortableLists = document.querySelectorAll('.sortable');
    sortableLists.forEach(list => {
        new Sortable(list, {
            handle: '.sort-handle',
            animation: 150,
            onEnd: function() {
                // Update order in database via AJAX
                const items = list.querySelectorAll('[data-id]');
                const order = Array.from(items).map((item, index) => ({
                    id: item.dataset.id,
                    order: index + 1
                }));
                
                fetch('update-order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        items: order,
                        table: list.dataset.table
                    })
                });
            }
        });
    });
});

// Helper Functions

/**
 * Generate URL-friendly slug from string
 */
function generateSlug(str) {
    return str
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

/**
 * Initialize special inputs (like date pickers, rich text editors, etc.)
 */
function initializeSpecialInputs(container) {
    // Date pickers
    const datePickers = container.querySelectorAll('.datepicker');
    datePickers.forEach(input => {
        new Pikaday({
            field: input,
            format: 'YYYY-MM-DD'
        });
    });
    
    // Rich text editors
    const editors = container.querySelectorAll('.rich-text');
    editors.forEach(editor => {
        ClassicEditor
            .create(editor)
            .catch(error => {
                console.error(error);
            });
    });
    
    // Color pickers
    const colorPickers = container.querySelectorAll('.colorpicker');
    colorPickers.forEach(input => {
        new Pickr({
            el: input,
            theme: 'classic',
            default: input.value || '#000000',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    clear: true,
                    save: true
                }
            }
        });
    });
    
    // Select2 dropdowns
    const select2Inputs = container.querySelectorAll('.select2');
    select2Inputs.forEach(select => {
        $(select).select2({
            width: '100%'
        });
    });
}

/**
 * Show success message
 */
function showSuccess(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-success';
    alert.textContent = message;
    
    const container = document.querySelector('.alert-container');
    if (container) {
        container.appendChild(alert);
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
}

/**
 * Show error message
 */
function showError(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger';
    alert.textContent = message;
    
    const container = document.querySelector('.alert-container');
    if (container) {
        container.appendChild(alert);
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}

/**
 * Format currency
 */
function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

/**
 * Format date
 */
function formatDate(date, format = 'YYYY-MM-DD') {
    return moment(date).format(format);
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
} 

// Modal functionality
const AdminModal = {
    // Create and show modal
    show: function(options) {
        const defaults = {
            type: 'info', // success, warning, error, info
            title: 'Notification',
            message: '',
            okText: 'OK',
            cancelText: 'Cancel',
            showCancel: false,
            onOk: null,
            onCancel: null
        };
        
        const settings = Object.assign({}, defaults, options);
        
        // First, remove any existing modals
        const existingModals = document.querySelectorAll('.admin-modal-overlay');
        existingModals.forEach(modal => {
            document.body.removeChild(modal);
        });
        
        // Create modal elements
        const overlay = document.createElement('div');
        overlay.className = 'admin-modal-overlay';
        
        const modal = document.createElement('div');
        modal.className = 'admin-modal';
        
        const header = document.createElement('div');
        header.className = 'admin-modal-header';
        
        const iconClass = {
            'success': 'fas fa-check',
            'warning': 'fas fa-exclamation-triangle',
            'error': 'fas fa-times-circle',
            'info': 'fas fa-info-circle'
        };
        
        const icon = document.createElement('div');
        icon.className = `admin-modal-icon ${settings.type}`;
        icon.innerHTML = `<i class="${iconClass[settings.type] || iconClass.info}"></i>`;
        
        const title = document.createElement('h3');
        title.className = 'admin-modal-title';
        title.textContent = settings.title;
        
        header.appendChild(icon);
        header.appendChild(title);
        
        const content = document.createElement('div');
        content.className = 'admin-modal-content';
        content.textContent = settings.message;
        
        const footer = document.createElement('div');
        footer.className = 'admin-modal-footer';
        
        const okButton = document.createElement('button');
        okButton.className = 'admin-modal-btn ok';
        okButton.textContent = settings.okText;
        
        if (settings.showCancel) {
            const cancelButton = document.createElement('button');
            cancelButton.className = 'admin-modal-btn cancel';
            cancelButton.textContent = settings.cancelText;
            
            cancelButton.addEventListener('click', function() {
                AdminModal.hide(overlay);
                if (typeof settings.onCancel === 'function') {
                    settings.onCancel();
                }
            });
            
            footer.appendChild(cancelButton);
        }
        
        okButton.addEventListener('click', function() {
            AdminModal.hide(overlay);
            if (typeof settings.onOk === 'function') {
                settings.onOk();
            }
        });
        
        footer.appendChild(okButton);
        
        modal.appendChild(header);
        modal.appendChild(content);
        modal.appendChild(footer);
        
        overlay.appendChild(modal);
        document.body.appendChild(overlay);
        
        // Force reflow to ensure CSS transitions work properly
        void overlay.offsetWidth;
        
        // Show modal - use setTimeout to ensure the transition works
        setTimeout(function() {
            overlay.classList.add('active');
        }, 10);
        
        return overlay;
    },
    
    // Hide and remove modal
    hide: function(overlay) {
        if (!overlay) return;
        
        overlay.classList.remove('active');
        
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 300); // Match the CSS transition time
    },
    
    // Convenience methods
    success: function(message, title = 'Success', okCallback = null) {
        return this.show({
            type: 'success',
            title: title,
            message: message,
            onOk: okCallback
        });
    },
    
    warning: function(message, title = 'Warning', okCallback = null) {
        return this.show({
            type: 'warning',
            title: title,
            message: message,
            onOk: okCallback
        });
    },
    
    error: function(message, title = 'Error', okCallback = null) {
        return this.show({
            type: 'error',
            title: title,
            message: message,
            onOk: okCallback
        });
    },
    
    confirm: function(message, title = 'Confirm', okCallback = null, cancelCallback = null) {
        return this.show({
            type: 'info',
            title: title,
            message: message,
            showCancel: true,
            onOk: okCallback,
            onCancel: cancelCallback
        });
    }
};

// Test modal function to demonstrate usage - call testModal() in console to test
function testModal() {
    AdminModal.success('This is a test success modal!');
}

// Display a test modal after the page loads - Remove this in production
document.addEventListener('DOMContentLoaded', function() {
    // For testing only - uncomment to test modals on page load
    // setTimeout(function() {
    //     AdminModal.success('Action completed successfully!');
    // }, 1000);
});

// Helper function to show alerts in the page
function showAlert(type, message, title = '') {
    const alertContainer = document.querySelector('.admin-alerts') || document.createElement('div');
    if (!alertContainer.classList.contains('admin-alerts')) {
        alertContainer.className = 'admin-alerts';
        const main = document.querySelector('.admin-main');
        if (main) {
            main.insertBefore(alertContainer, main.firstChild);
        } else {
            document.body.insertBefore(alertContainer, document.body.firstChild);
        }
    }
    
    const alert = document.createElement('div');
    alert.className = `admin-alert admin-alert-${type}`;
    alert.style.opacity = '0';
    
    const iconClass = {
        'success': 'fas fa-check-circle',
        'warning': 'fas fa-exclamation-triangle',
        'error': 'fas fa-times-circle',
        'info': 'fas fa-info-circle'
    };
    
    alert.innerHTML = `
        <div class="admin-alert-icon">
            <i class="${iconClass[type] || iconClass.info}"></i>
        </div>
        <div class="admin-alert-content">
            ${title ? `<div class="admin-alert-title">${title}</div>` : ''}
            <div class="admin-alert-message">${message}</div>
        </div>
        <button class="admin-alert-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    alertContainer.appendChild(alert);
    
    // Add close functionality
    const closeButton = alert.querySelector('.admin-alert-close');
    closeButton.addEventListener('click', function() {
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 300);
    });
    
    // Trigger reflow to enable transitions
    alert.offsetWidth;
    
    // Show alert
    alert.style.opacity = '1';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 300);
        }
    }, 5000);
    
    return alert;
}

// Usage examples:
// showAlert('success', 'Template added successfully!', 'Success');
// showAlert('error', 'Failed to upload file', 'Error');
// showAlert('warning', 'This is your last warning', 'Warning');
// showAlert('info', 'Check out the new features', 'Information'); 