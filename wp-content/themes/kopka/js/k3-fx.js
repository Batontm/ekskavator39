/**
 * Общие 3D-эффекты (вариант «3D»), на всех страницах:
 *  1. Наклон карточек за курсором с бликом (только мышь).
 *  2. После анимации появления снимаем её, чтобы наклон работал.
 *  3. Фоновые комья земли двигаются с разной скоростью при прокрутке.
 * При prefers-reduced-motion эффекты не включаются.
 */
(function () {
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;
  var fine = window.matchMedia && window.matchMedia('(pointer: fine)').matches;

  // 2. Анимации появления из темы держат transform через animation-fill.
  document.addEventListener('animationend', function (ev) {
    var t = ev.target;
    if (t && t.classList && t.classList.contains('visible')) t.classList.add('k3-done');
  });

  // 1. Наклон
  if (fine) {
    var SEL = '.fse-card, .fse-gallery-item, .k3-plaque, .fse-price-card, .fse-photo, .fse-faq-item, .k3-tilt';
    var MAX = 7;
    var current = null;
    function reset(el) {
      el.style.transform = '';
      el.classList.remove('k3-tilting');
    }
    document.addEventListener('mousemove', function (ev) {
      var el = ev.target && ev.target.closest ? ev.target.closest(SEL) : null;
      if (current && current !== el) { reset(current); current = null; }
      if (!el) return;
      // Большие блоки наклоняем слабее.
      var r = el.getBoundingClientRect();
      var big = r.width > 600 || r.height > 500;
      var m = el.classList.contains('fse-faq-item') ? 1.5 : (big ? 3 : MAX);
      var px = (ev.clientX - r.left) / r.width;
      var py = (ev.clientY - r.top) / r.height;
      el.style.transform = 'perspective(900px) rotateX(' + ((0.5 - py) * m).toFixed(2) + 'deg) rotateY(' + ((px - 0.5) * m).toFixed(2) + 'deg) translateZ(0)';
      el.style.setProperty('--k3-gx', (px * 100).toFixed(1) + '%');
      el.style.setProperty('--k3-gy', (py * 100).toFixed(1) + '%');
      el.classList.add('k3-tilting');
      current = el;
    }, { passive: true });
    document.addEventListener('mouseleave', function () { if (current) { reset(current); current = null; } });
  }

  // 3. Комья земли
  var parts = Array.prototype.slice.call(document.querySelectorAll('.k3-particle'));
  if (parts.length) {
    var raf = 0;
    function move() {
      raf = 0;
      var y = window.scrollY || 0;
      parts.forEach(function (p) {
        var s = parseFloat(p.getAttribute('data-speed')) || 0.5;
        // Комья отстают от прокрутки — кажутся дальше, чем контент.
        p.style.transform = 'translate3d(0,' + (y * s * 0.3).toFixed(1) + 'px,0) rotate(' + (y * s * 0.08).toFixed(1) + 'deg)';
      });
    }
    window.addEventListener('scroll', function () { if (!raf) raf = requestAnimationFrame(move); }, { passive: true });
    move();
  }
})();
