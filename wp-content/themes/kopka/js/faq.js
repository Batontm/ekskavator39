(function(){
  function bindFaq(root) {
  root.querySelectorAll('.fse-faq-item, .fse-accordion-item, [data-faq-toggle], [data-accordion]').forEach(function(item) {
    if (item.tagName === 'DETAILS' || item.dataset.pmgptFaqBound === '1') return;
    var trigger = item.querySelector('.fse-faq-question, .accordion-button, .faq-question, [data-faq-toggle], summary, button');
    var panel = item.querySelector('.fse-faq-answer, .accordion-body, .faq-answer, .panel');
    if (!trigger || !panel) return;
    item.dataset.pmgptFaqBound = '1';
    trigger.addEventListener('click', function(e) {
      if (trigger.tagName === 'SUMMARY') return;
      e.preventDefault();
      item.classList.toggle('is-open');
    });
  });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() { bindFaq(document); });
  } else {
    bindFaq(document);
  }
})();