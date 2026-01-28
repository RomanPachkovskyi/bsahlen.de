/**
 * BSahlen Custom JavaScript
 * Mega Menu Overlay and active state management
 */

document.addEventListener('DOMContentLoaded', function () {

  // ===== MEGA MENU OVERLAY =====

  // Create overlay element
  const overlay = document.createElement('div');
  overlay.className = 'bsa-mega-overlay';
  document.body.appendChild(overlay);

  // Find all menu items with mega menu
  const menuItems = document.querySelectorAll('.e-n-menu-item');
  if (!menuItems.length) return;

  let activeMenuItem = null;
  let isAnyMenuOpen = false;

  // Check if a specific menu's content is visible
  function isMenuContentVisible(menuItem) {
    const content = menuItem.querySelector('.e-n-menu-content');
    if (!content) return false;

    const style = window.getComputedStyle(content);
    return (
      style.display !== 'none' &&
      style.visibility !== 'hidden' &&
      content.offsetWidth > 0 &&
      content.offsetHeight > 0
    );
  }

  // Find which menu item has visible content
  function findOpenMenuItem() {
    for (let i = 0; i < menuItems.length; i++) {
      if (isMenuContentVisible(menuItems[i])) {
        return menuItems[i];
      }
    }
    return null;
  }

  // Update active states
  function updateState() {
    const openItem = findOpenMenuItem();
    const menuOpen = openItem !== null;

    // Update body class for overlay
    if (menuOpen !== isAnyMenuOpen) {
      document.body.classList.toggle('bsa-mega-open', menuOpen);
      isAnyMenuOpen = menuOpen;
    }

    // Update active menu item
    if (openItem !== activeMenuItem) {
      // Remove active class from previous item
      if (activeMenuItem) {
        activeMenuItem.classList.remove('bsa-mega-active');
      }

      // Add active class to new item
      if (openItem) {
        openItem.classList.add('bsa-mega-active');
      }

      activeMenuItem = openItem;
    }
  }

  // Check state with requestAnimationFrame for smooth updates
  function loop() {
    updateState();
    requestAnimationFrame(loop);
  }
  requestAnimationFrame(loop);

  // Close on overlay click
  overlay.addEventListener('click', function () {
    document.body.classList.remove('bsa-mega-open');
    if (activeMenuItem) {
      activeMenuItem.classList.remove('bsa-mega-active');
      activeMenuItem = null;
    }
  });

});
