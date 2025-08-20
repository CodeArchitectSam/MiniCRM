
function showNotification(status, title) {
    new Notify({
        status: status,
        title: title,
        effect: 'slide',
        type: 'filled',
    });
}

window.showNotification = showNotification;

const SpinnerUtils = {
    // Default spinner HTML (using TailwindCSS classes)
    spinnerHTML: `<span></span>`,
  
    // Show spinner on an element
    show: function(element, originalContentKey = 'originalContent') {
      if (!element) return;
      
      // Store original content if not already stored
      if (!element.dataset[originalContentKey]) {
        element.dataset[originalContentKey] = element.innerHTML;
      }
      
      // Add spinner and disable if it's a button
      element.innerHTML = this.spinnerHTML + (element.dataset.spinnerText ? ` ${element.dataset.spinnerText}` : '');
      
      if (element.tagName === 'BUTTON' || element.getAttribute('role') === 'button') {
        element.disabled = true;
      }
    },
  
    // Hide spinner and restore original content
    hide: function(element, originalContentKey = 'originalContent') {
      if (!element || !element.dataset[originalContentKey]) return;
      
      element.innerHTML = element.dataset[originalContentKey];
      
      if (element.tagName === 'BUTTON' || element.getAttribute('role') === 'button') {
        element.disabled = false;
      }
    },
  
    // Toggle spinner state
    toggle: function(element, originalContentKey = 'originalContent') {
      if (!element) return;
      
      if (element.dataset[originalContentKey]) {
        this.hide(element, originalContentKey);
      } else {
        this.show(element, originalContentKey);
      }
    },
  
    // Initialize spinner behavior for elements with specific attributes
    init: function() {
      // For buttons with data-spinner attribute
      document.querySelectorAll('[data-spinner]').forEach(btn => {
        btn.addEventListener('click', function() {
          SpinnerUtils.show(this);
        });
      });
      
      // For forms with data-spinner attribute
      document.querySelectorAll('form[data-spinner]').forEach(form => {
        form.addEventListener('submit', function() {
          const spinnerTarget = this.dataset.spinnerTarget 
            ? document.querySelector(this.dataset.spinnerTarget)
            : this.querySelector('button[type="submit"]');
          if (spinnerTarget) {
            SpinnerUtils.show(spinnerTarget);
          }
        });
      });
    }
  };
  
  // Initialize on DOM load
  document.addEventListener('DOMContentLoaded', function() {
    SpinnerUtils.init();
  });
  
  // Make it available globally if needed
  window.SpinnerUtils = SpinnerUtils;

  function showSpinner(elementId, text = 'Loading...') {
    const element = document.getElementById(elementId);
    element.dataset.spinnerText = text;
    SpinnerUtils.show(element);
  }

  function hideSpinner(elementId) {
    const element = document.getElementById(elementId);
    SpinnerUtils.hide(element);
  }

  window.showSpinner = showSpinner;
  window.hideSpinner = hideSpinner;