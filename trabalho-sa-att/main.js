document.addEventListener('DOMContentLoaded', () => {
    // --- 0. Authentication Guard ---
    const isLoggedIn = localStorage.getItem('wavify_logged_in') === 'true';
    const isLoginPage = window.location.pathname.includes('wavify_login.php') || 
                        window.location.pathname.includes('index.php') ||
                        window.location.pathname.endsWith('/');

    if (!isLoggedIn && !isLoginPage) {
        // Redirect to login if not logged in and not already on the login page
        window.location.href = 'index.php';
        return; // Stop execution of the rest of the script
    }

    // --- 1. Profile Persistence Logic ---
    const updateProfileUI = () => {
        const userName = localStorage.getItem('wavify_user_name') || 'Alex Storm';
        const userEmail = localStorage.getItem('wavify_user_email') || 'alex.storm@wavify.com';
        const userImg = localStorage.getItem('wavify_user_img') || 'https://lh3.googleusercontent.com/aida-public/AB6AXuBCocV5HrLtEgw93YC8-9gBUa8oK_RlDwmftAqIwtQK0pzKWgmoL37qgVhJ9h11mvCgngvgpVZtYCbol8X7NqGq1xPE8v5ucNSkpbDqkTlWY8bQzra2w8DZFMSyZD4_qrkoxdNEAwUQIHE_grh9XnUYyrEiIG0r2L-LdAcbnjZHJverIAzuDwe3DNcgdVh6mHjcV0vsa-C1somLph2WLk2KRHrt_j9jy-8G9uyZqeKEANaFZmLAEQ_tLIVtLthqExlde2GgsRaJUdM';

        // Update all profile images on the page
        const profileImgs = document.querySelectorAll('#header-profile-img, #mobile-header-profile-img, #profile-preview-img');
        profileImgs.forEach(img => img.src = userImg);

        // Update name displays
        const nameDisplay = document.getElementById('display-name');
        if (nameDisplay) nameDisplay.textContent = userName;

        const nameInput = document.getElementById('user-name');
        if (nameInput) nameInput.value = userName;

        // Update email display in profile page
        const emailInput = document.getElementById('user-email-input');
        if (emailInput) emailInput.value = userEmail;
    };

    updateProfileUI();

    // Profile Page Specific Logic
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        const profileUpload = document.getElementById('profile-upload');
        const profilePreview = document.getElementById('profile-preview-img');
        const saveStatus = document.getElementById('save-status');

        // Image Preview Handler
        profileUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    profilePreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Form Submit Handler
        profileForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const newName = document.getElementById('user-name').value;
            const newImg = profilePreview.src;

            localStorage.setItem('wavify_user_name', newName);
            localStorage.setItem('wavify_user_img', newImg);

            // Show success message
            saveStatus.classList.remove('hidden');
            setTimeout(() => {
                saveStatus.classList.add('hidden');
                updateProfileUI(); // Refresh header/mobile images
            }, 2000);
        });
    }

    // --- 2. Existing Navigation & Player Logic ---
    
    // Upload Area Interaction
    const uploadArea = document.querySelector('.border-dashed');
    if (uploadArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('border-primary', 'bg-primary/10');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('border-primary', 'bg-primary/10');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            let dt = e.dataTransfer;
            let files = dt.files;
            if (files.length > 0) {
                alert(`Arquivo "${files[0].name}" adicionado com sucesso à fila de upload!`);
            }
        }
    }

    // Like button toggle
    const allButtons = document.querySelectorAll('button .material-symbols-outlined');
    allButtons.forEach(icon => {
        if (icon.textContent.trim() === 'favorite_border' || icon.textContent.trim() === 'favorite') {
            icon.parentElement.addEventListener('click', (e) => {
                e.preventDefault();
                if (icon.textContent.trim() === 'favorite_border') {
                    icon.textContent = 'favorite';
                    icon.style.fontVariationSettings = "'FILL' 1";
                    icon.classList.add('text-primary');
                } else {
                    icon.textContent = 'favorite_border';
                    icon.style.fontVariationSettings = "'FILL' 0";
                    icon.classList.remove('text-primary');
                }
            });
        }
    });

    // --- 3. Logout Session Handling ---
    const logoutLinks = document.querySelectorAll('a[href="wavify_login.php"], a[href="index.php"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            // Clear login flag
            localStorage.removeItem('wavify_logged_in');
            // Redirection happens normally via href
        });
    });
});
