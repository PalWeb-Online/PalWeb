import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
          template: {
            transformAssetUrls: {
              base: null,
              includeAbsolute: false,
            }
          }
        }),
        VitePWA({
            buildBase: '/build/',
            scope: '/',
            base: '/',
            registerType: 'autoUpdate',
            injectRegister: 'auto',
            strategies: 'injectManifest',
            srcDir: 'resources/js',
            filename: 'sw.js',
            devOptions: { enabled: false },
            manifest: {
                name: 'PalWeb',
                short_name: 'PalWeb',
                background_color: '#4D0DA5FF',
                theme_color: '#F7B83BFF',
                display: 'standalone',
                id: '/',
                scope: '/',
                start_url: '/',
                icons: [
                    { src: '/icons/icon152.png', sizes: '152x152', type: 'image/png' },
                    { src: '/icons/icon167.png', sizes: '167x167', type: 'image/png' },
                    { src: '/icons/icon180.png', sizes: '180x180', type: 'image/png' },
                    { src: '/icons/icon192.png', sizes: '192x192', type: 'image/png' },
                    { src: '/icons/icon512.png', sizes: '512x512', type: 'image/png' },
                ],
            },
            workbox: {
                globDirectory: 'public/build/',
                globPatterns: ['**/*.{js,css,woff2,woff,ttf,png,jpg,svg}'],

                additionalManifestEntries: [
                    { url: '/', revision: `${Date.now()}` },
                    { url: '/offline', revision: `${Date.now()}` },
                    { url: '/icons/icon152.png', revision: `${Date.now()}` },
                    { url: '/icons/icon167.png', revision: `${Date.now()}` },
                    { url: '/icons/icon180.png', revision: `${Date.now()}` },
                    { url: '/icons/icon192.png', revision: `${Date.now()}` },
                    { url: '/icons/icon512.png', revision: `${Date.now()}` },
                ],

                navigateFallback: '/offline',

                runtimeCaching: [
                    {
                        urlPattern: /\.(?:js|css|png|jpg|svg|woff2|woff|ttf)$/,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'static-assets',
                            expiration: { maxEntries: 200, maxAgeSeconds: 60 * 60 * 24 * 30 },
                        },
                    },
                    {
                        urlPattern: /^https:\/\/[^/]+\/api\//,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-calls',
                            networkTimeoutSeconds: 10,
                            expiration: { maxEntries: 100, maxAgeSeconds: 60 * 60 * 24 },
                        },
                    },
                ],

                maximumFileSizeToCacheInBytes: 3000000,
            },
        }),
    ],
});
