(function (wp, settings) {
  const el = wp.element.createElement;
  const { Placeholder, SelectControl } = wp.components;

  wp.blocks.registerBlockType('breedio/foal-coat', {
    edit({ attributes, setAttributes }) {
      const { labels, modes, stallions } = settings;
      const options = [{ value: '0', label: labels.pageStallion }].concat(
        stallions.map((stallion) => ({ value: String(stallion.id), label: stallion.name })),
      );
      return el(
        'div',
        wp.blockEditor.useBlockProps(),
        el(
          Placeholder,
          { icon: 'art', label: labels.title, instructions: stallions.length ? labels.preview : labels.empty },
          stallions.length > 0 &&
            el(
              'div',
              { style: { display: 'grid', gap: '12px', width: '100%' } },
              el(SelectControl, {
                label: labels.mode,
                value: attributes.mode,
                options: modes,
                onChange: (mode) => setAttributes({ mode }),
                __nextHasNoMarginBottom: true,
              }),
              attributes.mode !== 'match' &&
                el(SelectControl, {
                  label: labels.stallion,
                  value: String(attributes.stallion),
                  options,
                  onChange: (value) => setAttributes({ stallion: parseInt(value, 10) }),
                  __nextHasNoMarginBottom: true,
                }),
            ),
        ),
      );
    },
  });
})(window.wp, window.breedioFoalCoat);
