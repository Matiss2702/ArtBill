import { getIcon } from './iconList.js';
import resolveConfig from 'tailwindcss/resolveConfig';
import tailwindConfig from '../../tailwind.config.js';

const tw = resolveConfig(tailwindConfig);

$(document).ready(function () {
    $('icon').each(function () {
        const icon = {
            name: $(this).attr('name'),
            style: $(this).attr('display') || 'outline',
            color: tw.theme.colors[$(this).attr('color')] || 'currentColor',
            variant: $(this).attr('variant') || null,
            size: $(this).attr('size') || '5',
            viewBox: $(this).attr('viewBox') || '24',
            strokeWidth: $(this).attr('stroke-width') || '1.5',
            extraClasses: $(this).attr('class') || '',
        };

        if (icon.variant && icon.color !== 'currentColor') {
            icon.color = tw.theme.colors[$(this).attr('color')][icon.variant];
        }

        if (!icon.name) {
            $(this).remove();
            return;
        }

        let colorProperties = `fill="none" stroke="${icon.color}" stroke-width="${icon.strokeWidth}"`;
        if (icon.style === 'solid' || icon.style === 'mini') {
            colorProperties = `fill="${icon.color}"`;
        }

        if (icon.style === 'mini') {
            icon.viewBox = '20';
        }

        const svg = `<svg class="w-${icon.size} h-${icon.size}" ${colorProperties} data-slot="icon" viewBox="0 0 ${icon.viewBox} ${icon.viewBox}" xmlns="http://www.w3.org/2000/svg">`;
        const path = getIcon(icon.name, icon.style);

        $(this).replaceWith(`${svg}${path}</svg>`);
    });
});
