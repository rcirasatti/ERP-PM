{{-- Form Validation Script Component --}}
<script>
    /**
     * Generic form validation untuk cek field wajib sebelum submit
     * @param {Array} fieldsToCheck - Array berisi object dengan {id, label}
     * @returns {void}
     */
    function initializeFormValidation(fieldsToCheck = []) {
        const form = document.querySelector('form');
        if (!form) return;
        
        form.addEventListener('submit', function(e) {
            const missingFields = [];
            
            // Auto-check semua input dengan required attribute jika tidak ada fieldsToCheck
            if (fieldsToCheck.length === 0) {
                const requiredInputs = form.querySelectorAll('[required]');
                requiredInputs.forEach(input => {
                    const label = form.querySelector(`label[for="${input.id}"]`)?.textContent || input.name || input.id;
                    const labelText = label.replace(/\*$/g, '').trim();
                    
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        const group = form.querySelectorAll(`[name="${input.name}"]:checked`);
                        if (group.length === 0) {
                            missingFields.push(labelText);
                        }
                    } else if (input.type === 'file') {
                        if (!input.files || input.files.length === 0) {
                            missingFields.push(labelText);
                        }
                    } else if (input.tagName === 'SELECT') {
                        if (!input.value) {
                            missingFields.push(labelText);
                        }
                    } else if (input.tagName === 'TEXTAREA') {
                        if (!input.value.trim()) {
                            missingFields.push(labelText);
                        }
                    } else {
                        if (!input.value) {
                            missingFields.push(labelText);
                        }
                    }
                });
            } else {
                // Manual check dengan fieldsToCheck parameter
                fieldsToCheck.forEach(field => {
                    const input = document.getElementById(field.id);
                    if (!input) return;
                    
                    const label = field.label || input.name || input.id;
                    
                    if (input.type === 'file') {
                        if (!input.files || input.files.length === 0) {
                            missingFields.push(label);
                        }
                    } else if (input.tagName === 'SELECT') {
                        if (!input.value) {
                            missingFields.push(label);
                        }
                    } else if (input.tagName === 'TEXTAREA') {
                        if (!input.value.trim()) {
                            missingFields.push(label);
                        }
                    } else {
                        if (!input.value) {
                            missingFields.push(label);
                        }
                    }
                });
            }
            
            if (missingFields.length > 0) {
                e.preventDefault();
                const uniqueFields = [...new Set(missingFields)];
                const fieldList = uniqueFields.join(',\n• ');
                alert('⚠️ Field wajib diisi:\n\n• ' + fieldList + '\n\nMohon lengkapi semua field yang diperlukan!');
                return false;
            }
        });
    }
    
    // Auto-initialize saat document ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => initializeFormValidation());
    } else {
        initializeFormValidation();
    }
</script>
