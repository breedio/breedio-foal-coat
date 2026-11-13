addEventListener('message', function (event) {
  var data = event.data;
  if (!data || typeof data.type !== 'string' || data.type.indexOf('breedio:coat-') !== 0) return;
  document.querySelectorAll('iframe[data-breedio-foal-coat]').forEach(function (frame) {
    if (frame.contentWindow !== event.source || frame.dataset.breedioFoalCoat !== event.origin) return;
    if (data.type === 'breedio:coat-height') {
      if (typeof data.height === 'number') frame.style.height = data.height + 'px';
      return;
    }
    dispatchEvent(new CustomEvent(data.type.replace('breedio:coat-', 'breedio-foal-coat:'), { detail: { message: data, frame: frame } }));
  });
});
