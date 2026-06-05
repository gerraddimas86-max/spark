import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/three-scene.js',
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: ['three', 'gsap', 'three/examples/jsm/controls/OrbitControls.js']
    }
});