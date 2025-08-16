$(document).ready(function() {
    // Wait a bit for the original menu system to initialize
    setTimeout(function() {
        // Function to set active menu states
        function setActiveMenuStates() {
            // Get current URL path
            var currentPath = window.location.pathname;
            
            // Remove all active classes first
            $('.main-menu-content li').removeClass('active sidebar-group-active');
            
            // Find the menu item that matches current path
            var activeMenuItem = $('.main-menu-content a[href="' + currentPath + '"]');
            
            if (activeMenuItem.length > 0) {
                // Add active class to the current menu item
                activeMenuItem.closest('li').addClass('active');
                
                // If it's a child menu item, add active classes to parent
                var parentLi = activeMenuItem.closest('li.nav-item.has-sub');
                if (parentLi.length > 0) {
                    parentLi.addClass('sidebar-group-active active open');
                }
                
                // Add active class to all parent li elements
                activeMenuItem.parents('li').addClass('sidebar-group-active active');
            }
            
            // Handle special cases for routes that might not match exactly
            handleSpecialRoutes(currentPath);
        }
        
        // Handle special routes that might have different patterns
        function handleSpecialRoutes(currentPath) {
            // Handle dashboard route
            if (currentPath === '/dashboard' || currentPath === '/') {
                setParentActive('dashboard');
            }
            
            // Handle employee routes
            if (currentPath.includes('/employees') || currentPath.includes('/employee/')) {
                setParentActive('employees.index');
            }
            
            // Handle attendance routes
            if (currentPath.includes('/attendance')) {
                setParentActive('attendance.index');
            }
            
            // Handle expense routes
            if (currentPath.includes('/expenses') || currentPath.includes('/expense/')) {
                setParentActive('expense.index');
            }
            
            // Handle salary routes
            if (currentPath.includes('/salaries') || currentPath.includes('/salary/')) {
                setParentActive('salary.index');
            }
            
            // Handle report routes
            if (currentPath.includes('/reports')) {
                setParentActive('reports.index');
            }
        }
        
        // Helper function to set parent menu active
        function setParentActive(routeName) {
            var menuLink = $('.main-menu-content a[data-route="' + routeName + '"]');
            if (menuLink.length > 0) {
                menuLink.closest('li').addClass('active');
                var parentLi = menuLink.closest('li.nav-item.has-sub');
                if (parentLi.length > 0) {
                    parentLi.addClass('sidebar-group-active active open');
                }
                menuLink.parents('li').addClass('sidebar-group-active active');
            }
        }
        
        // Initialize on page load
        setActiveMenuStates();
        
        // Ensure the original menu system's toggle functionality is preserved
        // We'll use event delegation to avoid conflicts
        
        // Handle child menu item clicks to maintain parent state
        $(document).on('click', '.main-menu-content .menu-content a', function(e) {
            var $this = $(this);
            var $parent = $this.closest('li.nav-item.has-sub');
            
            // Keep parent open when child is clicked
            $parent.addClass('open');
            
            // Add active class to clicked item and remove from siblings
            $this.closest('li').addClass('active');
            $this.siblings().closest('li').removeClass('active');
        });
        
        // Ensure parent menus stay open when navigating to child pages
        $(document).on('click', '.main-menu-content .menu-content a', function() {
            var $this = $(this);
            var $parent = $this.closest('li.nav-item.has-sub');
            
            // Ensure parent stays open
            setTimeout(function() {
                $parent.addClass('open');
            }, 100);
        });
        
        // Add a small delay to ensure menu toggles work properly
        $(document).on('click', '.main-menu-content .nav-item.has-sub > a', function(e) {
            // Let the original menu system handle the toggle
            // We just ensure our active states are maintained
            setTimeout(function() {
                var $parent = $(e.currentTarget).closest('li.nav-item.has-sub');
                if ($parent.hasClass('sidebar-group-active')) {
                    $parent.addClass('open');
                }
            }, 50);
        });
        
    }, 300); // Wait 300ms for original menu system to initialize
});
