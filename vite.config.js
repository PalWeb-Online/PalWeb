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
                theme_color: '#4D0DA5FF',
                background_color: '#F7B83BFF',
                display: 'standalone',
                id: '/',
                scope: '/',
                start_url: '/',
                icons: [
                    { src: '/icons/icon-152.png', sizes: '152x152', type: 'image/png', purpose: 'any' },
                    { src: '/icons/icon-167.png', sizes: '167x167', type: 'image/png', purpose: 'any' },
                    { src: '/icons/icon-180.png', sizes: '180x180', type: 'image/png', purpose: 'any' },
                    { src: '/icons/icon-192.png', sizes: '192x192', type: 'image/png', purpose: 'any' },
                    { src: '/icons/icon-512.png', sizes: '512x512', type: 'image/png', purpose: 'any' },
                    { src: '/icons/icon-152-maskable.png', sizes: '152x152', type: 'image/png', purpose: 'maskable' },
                    { src: '/icons/icon-167-maskable.png', sizes: '167x167', type: 'image/png', purpose: 'maskable' },
                    { src: '/icons/icon-180-maskable.png', sizes: '180x180', type: 'image/png', purpose: 'maskable' },
                    { src: '/icons/icon-192-maskable.png', sizes: '192x192', type: 'image/png', purpose: 'maskable' },
                    { src: '/icons/icon-512-maskable.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
                ],
            },
            workbox: {
                globDirectory: 'public/build/',
                globPatterns: ['**/*.{js,css,woff2,woff,ttf,png,jpg,svg}'],

                additionalManifestEntries: [
                    { url: '/', revision: `${Date.now()}` },
                    { url: '/offline', revision: `${Date.now()}` },
                    { url: '/icons/icon-152.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-167.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-180.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-192.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-512.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-152-maskable.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-167-maskable.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-180-maskable.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-192-maskable.png', revision: `${Date.now()}` },
                    { url: '/icons/icon-512-maskable.png', revision: `${Date.now()}` },
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
