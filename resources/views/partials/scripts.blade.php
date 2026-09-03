<script>
    // =============================================
    // TOGGLE SIDEBAR DESKTOP
    // =============================================
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar-desktop');
        const mainContent = document.querySelector('.main-content');
        
        if (!sidebar) return;
        
        sidebar.classList.toggle('hidden');
        
        if (mainContent) {
            if (sidebar.classList.contains('hidden')) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            } else {
                mainContent.classList.remove('ml-0');
                mainContent.classList.add('ml-64');
            }
        }
        
        // Sauvegarder l'état
        localStorage.setItem('sidebarHidden', sidebar.classList.contains('hidden') ? 'true' : 'false');
    }

    // =============================================
    // TOGGLE SIDEBAR MOBILE
    // =============================================
    function toggleSidebarMobile() {
        const sidebar = document.getElementById('sidebarMobile');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar) {
            sidebar.classList.toggle('open');
        }
        if (overlay) {
            overlay.classList.toggle('active');
        }
        
        document.body.classList.toggle('overflow-hidden');
    }

    // =============================================
    // FERMER SIDEBAR MOBILE
    // =============================================
    function closeSidebarMobile() {
        const sidebar = document.getElementById('sidebarMobile');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar) {
            sidebar.classList.remove('open');
        }
        if (overlay) {
            overlay.classList.remove('active');
        }
        document.body.classList.remove('overflow-hidden');
    }

    // =============================================
    // RESTAURER L'ÉTAT DE LA SIDEBAR
    // =============================================
    function restoreSidebarState() {
        const isHidden = localStorage.getItem('sidebarHidden') === 'true';
        const sidebar = document.querySelector('.sidebar-desktop');
        const mainContent = document.querySelector('.main-content');
        
        if (!sidebar) return;
        
        if (isHidden) {
            sidebar.classList.add('hidden');
            if (mainContent) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            }
        } else {
            sidebar.classList.remove('hidden');
            if (mainContent) {
                mainContent.classList.remove('ml-0');
                mainContent.classList.add('ml-64');
            }
        }
    }

    // =============================================
    // FERMER MOBILE EN CLIQUANT À L'EXTÉRIEUR
    // =============================================
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebarMobile');
        const toggleBtn = document.getElementById('toggleSidebarMobileBtn');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar && sidebar.classList.contains('open')) {
            if (overlay && overlay.contains(event.target)) {
                closeSidebarMobile();
            } else if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                closeSidebarMobile();
            }
        }
    });

    // =============================================
    // CHARGER L'ÉTAT AU DÉMARRAGE
    // =============================================
    document.addEventListener('DOMContentLoaded', function() {
        restoreSidebarState();
    });

    // =============================================
    // CONFIRMATION DE SUPPRESSION
    // =============================================
    function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer ce contact ?') {
        return confirm(message);
    }

    // =============================================
    // AUTO-DISSIMULATION DES MESSAGES FLASH
    // =============================================
    setTimeout(function() {
        document.querySelectorAll('.alert-flash').forEach(function(el) {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 500);
        });
    }, 5000);
</script>