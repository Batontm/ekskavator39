/**
 * Псевдо-3D сцены (вариант «3D»): параллакс слоёв от мыши и «разбор»
 * при прокрутке. Работает для всех [data-k3-stage] на странице.
 *
 * Атрибуты слоя:
 *   data-depth — насколько сильно слой следует за мышью (px у края сцены);
 *   data-ex / data-ey — смещение слоя при полном разборе (px);
 *   data-rot — поворот при полном разборе (градусы);
 *   data-o0 / data-op — прозрачность до и после разбора;
 *   data-tag — подпись: проявляется во второй половине разбора.
 * Режим сцены data-k3-mode:
 *   hero   — разбирается, когда сцена уходит вверх;
 *   inview — разбирается, когда сцена доходит до середины экрана.
 * При prefers-reduced-motion всё остаётся на местах (подписи видны).
 */
(function () {
  var stages = Array.prototype.slice.call(document.querySelectorAll('[data-k3-stage]'));
  if (!stages.length) return;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) {
    stages.forEach(function (s) {
      s.classList.add('k3-static');
      // SMIL-анимации (стрелка часов, подсветка зон) тоже останавливаем.
      Array.prototype.forEach.call(s.querySelectorAll('svg'), function (svg) {
        if (svg.pauseAnimations) svg.pauseAnimations();
      });
    });
    return;
  }
  var fine = window.matchMedia && window.matchMedia('(pointer: fine)').matches;

  function num(el, name, def) {
    var v = el.getAttribute(name);
    return v === null ? def : parseFloat(v);
  }

  var items = stages.map(function (stage) {
    var layers = Array.prototype.slice.call(stage.querySelectorAll('[data-depth]')).map(function (el) {
      return {
        el: el,
        d: num(el, 'data-depth', 0),
        ex: num(el, 'data-ex', 0),
        ey: num(el, 'data-ey', 0),
        rot: num(el, 'data-rot', 0),
        o0: num(el, 'data-o0', 1),
        op: num(el, 'data-op', null),
        tag: el.hasAttribute('data-tag')
      };
    });
    var it = { stage: stage, layers: layers, mode: stage.getAttribute('data-k3-mode') || 'hero', mx: 0, my: 0, tx: 0, ty: 0, e: 0, visible: true, k: 1 };
    if (fine) {
      stage.addEventListener('mousemove', function (ev) {
        var r = stage.getBoundingClientRect();
        it.mx = ((ev.clientX - r.left) / r.width) * 2 - 1;
        it.my = ((ev.clientY - r.top) / r.height) * 2 - 1;
        schedule();
      });
      stage.addEventListener('mouseleave', function () { it.mx = 0; it.my = 0; schedule(); });
    }
    stage.classList.add('k3-live');
    return it;
  });

  function progress(it) {
    var r = it.stage.getBoundingClientRect();
    var vh = window.innerHeight || 800;
    it.visible = r.bottom > -100 && r.top < vh + 100;
    // Глубина параллакса пропорциональна размеру сцены (на телефоне меньше).
    it.k = Math.max(0.45, Math.min(1, r.width / 640));
    var p;
    if (it.mode === 'inview') {
      p = (vh * 0.92 - r.top) / (vh * 0.55);
    } else {
      p = (-r.top + vh * 0.12) / (r.height * 0.6);
    }
    return Math.max(0, Math.min(1, p));
  }

  var raf = 0;
  function render() {
    raf = 0;
    var again = false;
    items.forEach(function (it) {
      it.e = progress(it);
      if (!it.visible) return;
      it.tx += (it.mx - it.tx) * 0.12;
      it.ty += (it.my - it.ty) * 0.12;
      if (Math.abs(it.mx - it.tx) > 0.002 || Math.abs(it.my - it.ty) > 0.002) again = true;
      var e = it.e, ease = e * e * (3 - 2 * e), k = it.k;
      it.layers.forEach(function (l) {
        var x = (it.tx * l.d + l.ex * ease) * k;
        var y = (it.ty * l.d + l.ey * ease) * k;
        var t = 'translate3d(' + x.toFixed(1) + 'px,' + y.toFixed(1) + 'px,0)';
        if (l.rot) t += ' rotate(' + (l.rot * ease).toFixed(2) + 'deg)';
        l.el.style.transform = t;
        if (l.tag) {
          l.el.style.opacity = Math.max(0, Math.min(1, (e - 0.2) / 0.45)).toFixed(2);
        } else if (l.op !== null) {
          l.el.style.opacity = (l.o0 + (l.op - l.o0) * ease).toFixed(2);
        }
      });
    });
    if (again) schedule();
  }
  function schedule() { if (!raf) raf = requestAnimationFrame(render); }

  window.addEventListener('scroll', schedule, { passive: true });
  window.addEventListener('resize', schedule);
  schedule();
})();
