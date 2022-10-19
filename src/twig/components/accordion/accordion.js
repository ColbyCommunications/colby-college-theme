// import './_accordion.scss';

const buttons = document.querySelectorAll('[data-accordion-button]');

for (let i = 0; i < buttons.length; i++) {

  buttons[i].addEventListener('click', (e) => {
    const toggle = (e.target.getAttribute('aria-expanded') === 'true') ? false : true;
    const root = buttons[i].closest('[data-accordion-container]');
    const panel = buttons[i].closest('[data-accordion-panel]');
    const panelSiblings = getSiblings(panel);
    const window = panel.querySelector('[data-accordion-window]');
    const content = panel.querySelector('[data-accordion-content]');

    buttons[i].setAttribute('aria-expanded', toggle);
    buttons[i].setAttribute('tabindex', '0');
    toggle ? 
      window.setAttribute('style', `height: ${content.offsetHeight}px; visibility: visible;`) : 
      window.setAttribute('style', 'height: 0; visibility: hidden;');

    if (root.getAttribute('data-accordion-config-single') === 'true') {
      panelSiblings.forEach((sibling) => {
        const siblingButton = sibling.querySelector('[data-accordion-button]');
        const siblingWindow = sibling.querySelector('[data-accordion-window]');
  
        siblingButton.setAttribute('aria-expanded', 'false');
        siblingButton.setAttribute('tabindex', '-1');
        siblingWindow.setAttribute('style', 'height: 0; visibility: hidden;');
      });
    }
  });

  // Arrow key controls
  buttons[i].addEventListener('keydown', (e) => {

    if (e.keyCode === 38) {
      if (i === 0) {
        buttons[buttons.length - 1].focus();
      } else {
        buttons[i - 1].focus();
      }
    }

    if (e.keyCode === 40) {
      if (i === buttons.length - 1) {
        buttons[0].focus();
      } else {
        buttons[i + 1].focus();
      }
    }
  });
}

// This should probably be extracted into a more
// global set of helper functions for pure JS
// engineers down the road. - Greg S.
function getSiblings(element) {
  let siblings = [];
  let sibling = element.parentNode.firstChild;

  while (sibling) {
    if (sibling.nodeType === 1 && sibling !== element) {
			siblings.push(sibling);
    }
    sibling = sibling.nextSibling
  }

  return siblings;
};

console.log('accordion.js is in the building');