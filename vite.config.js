import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';

function themeInputs() {
    const inputs = [];
    const themesDir = 'resources/themes';

    if (! fs.existsSync(themesDir)) {
        return inputs;
    }

    for (const theme of fs.readdirSync(themesDir)) {
        const themePath = path.join(themesDir, theme);

        if (! fs.statSync(themePath).isDirectory()) {
            continue;
        }

        const css = path.join(themePath, 'css', 'app.css');
        const js = path.join(themePath, 'js', 'app.js');

        if (fs.existsSync(css)) {
            inputs.push(css);
        }

        if (fs.existsSync(js)) {
            inputs.push(js);
        }
    }

    return inputs;
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/filament/admin/theme.css',
                ...themeInputs(),
            ],
            refresh: [
                'resources/views/**',
                'resources/themes/**',
                'routes/**',
            ],
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
