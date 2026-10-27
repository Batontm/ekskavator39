/**
 * Раскрытие номера телефона (см. inc/phone-guard.php).
 * В HTML номер замаскирован: «+7 (952) 110-••-••», ссылки — href="#".
 *  - Компьютер: номер раскрывается при наведении курсора или фокусе
 *    с клавиатуры; клик по кнопке — звонок (на ноутбуке откроется
 *    приложение для звонков, если оно есть).
 *  - Телефон: первое же нажатие раскрывает номер и открывает набор.
 * Раскрытый номер остаётся видимым до перезагрузки страницы.
 */
(function () {
  function decode(code) {
    try { return atob(code).split('').reverse().join(''); } catch (e) { return ''; }
  }
  function pretty(raw) {
    // +79521100609 → +7 (952) 110-06-09
    var d = raw.replace(/\D/g, '');
    if (d.length === 11) {
      return '+' + d[0] + ' (' + d.substr(1, 3) + ') ' + d.substr(4, 3) + '-' + d.substr(7, 2) + '-' + d.substr(9, 2);
    }
    return raw;
  }
  function revealSpan(span) {
    if (span.getAttribute('data-k3-open')) return;
    var raw = decode(span.getAttribute('data-k3p'));
    if (!raw) return;
    span.textContent = pretty(raw);
    span.setAttribute('data-k3-open', '1');
    span.removeAttribute('title');
  }
  function revealLink(a) {
    var raw = decode(a.getAttribute('data-k3p'));
    if (!raw) return '';
    a.setAttribute('href', 'tel:' + raw);
    Array.prototype.forEach.call(a.querySelectorAll('.k3-phone'), revealSpan);
    return raw;
  }
  function reveal(el) {
    var link = el.closest ? el.closest('a[data-k3p]') : null;
    if (link) revealLink(link);
    else revealSpan(el);
  }

  var hover = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var SEL = '[data-k3p]';

  if (hover) {
    document.addEventListener('mouseover', function (ev) {
      var el = ev.target.closest && ev.target.closest(SEL);
      if (el) reveal(el);
    });
  }
  document.addEventListener('focusin', function (ev) {
    var el = ev.target.closest && ev.target.closest(SEL);
    if (el) reveal(el);
  });
  document.addEventListener('click', function (ev) {
    var el = ev.target.closest && ev.target.closest(SEL);
    if (!el) return;
    var link = el.matches('a[data-k3p]') ? el : el.closest('a[data-k3p]');
    if (link) {
      var raw = revealLink(link);
      if (raw) {
        ev.preventDefault();
        window.location.href = 'tel:' + raw;
      }
    } else {
      revealSpan(el);
    }
  });
})();
