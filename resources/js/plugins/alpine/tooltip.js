import  tippy, { followCursor } from 'tippy.js';

export default function (Alpine) {
    Alpine.directive('tooltip', (el, { expression }, { evaluate }) => {
        let options = evaluate(expression);
        Object.keys(options).forEach((key) => {
            if (key === 'text') {
                options.content = options.text;
                delete options[key];
            }

            if (key === 'followCursor') {
                options.plugins = [followCursor];
            }
        });
        tippy(el, options);
    });

    tippy.setDefaultProps({
        theme: 'custom',
    });
}
