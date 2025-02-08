import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tsconfigPaths from 'vite-tsconfig-paths';
import copy from 'rollup-plugin-copy'
import { resolve } from 'path';
import * as fs from "node:fs";


export default defineConfig(({ mode }) => {

    // Ensure fallback to 'development'
    const envMode = mode === 'production' ? 'production' : 'development';

    // Load environment variables based on the current mode (development, production, etc.)
    const env = loadEnv(mode, process.cwd(), '');
    const date = new Date();
    const DOMAIN_NAME = env.SERVER_DOMAIN;
    const APP_URL = env.APP_URL;

    // Log build information
    console.log(`\n🚀 Starting Vite Build`);
    console.log(`🔹 Mode: ${envMode}`);
    console.log(`🔹 Domain: ${DOMAIN_NAME}`);
    console.log(`🔹 APP_URL: ${APP_URL}`);
    console.log(`🔹 Date: ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`);
    console.log(`🔹 Node.js Version: ${process.version}`);
    // console.log(`🔹 Vite Version: ${require('vite/package.json').version}`);
    console.log(`🔹 VITE_DEV_SERVER_URL: ${env.VITE_DEV_SERVER_URL}`);
    console.log(`🔹 TypeScript Support: ✅`);
    console.log(`🔹 React + JSX Support: ✅`);
    console.log(`🔹 SASS Support: ✅`);
    console.log(`🔹 Ziggy.js Alias: ${resolve(__dirname, 'vendor/tightenco/ziggy/dist')}`);
    console.log(`🔹 Minification: ${envMode === 'production' ? 'Enabled' : 'Disabled'}`);
    console.log(`🔹 Source Maps: ${envMode === 'development' ? 'Enabled' : 'Disabled'}`);
    console.log(`====================================\n`);

    return {
        plugins: [
            laravel({
                input: [
                    'resources/js/app.tsx', // Main TypeScript entry point for React
                    'resources/css/app.scss' // Main SASS entry point
                ],
                ssr: 'resources/js/ssr.tsx', // Define SSR entry point
                refresh: true, // Enables hot module replacement
            }),
            react(), // Enables React and JSX support
            // Automatically resolves paths from tsconfig.json
            tsconfigPaths(), // @see https://github.com/aleclarson/vite-tsconfig-paths
            // Copy assets after build
            copy({ // @see https://github.com/vladshcherbin/rollup-plugin-copy
                targets: [
                    { src: 'public/build/.vite/manifest.json', dest: 'public/build' },
                    { src: 'public/build/assets/app.js', dest: 'public/assets/js' },
                    { src: 'public/build/assets/app.css', dest: 'public/assets/css' }
                ]
            }),
        ],
        resolve: {
            alias: {
                '@': resolve(__dirname, 'resources/js'), // Short alias for cleaner imports
                ziggy: resolve(__dirname, 'vendor/tightenco/ziggy/dist'), // Ziggy alias
            },
        },
        css: {
            preprocessorOptions: {
                scss: {
                    //additionalData: `@import "resources/css/_variables.scss";`, // Example global SASS file
                },
            },
        },
        server: {
            host: '0.0.0.0', // Allows Vite to be accessed from the Docker network
            port: 5173, // Default Vite port
            strictPort: true, // Ensures Vite only runs on the specified port
            https: {
                key: fs.readFileSync(`/etc/ssl/certs/secret.${DOMAIN_NAME}.key`),
                cert: fs.readFileSync(`/etc/ssl/certs/server.${DOMAIN_NAME}.crt`),
            },
            hmr: {
                protocol: env.VITE_HMR_PROTOCOL,
                host: env.SERVER_DOMAIN, // Ensure this matches what your browser can access
                clientPort: 5173,
            },
            watch: {
                usePolling: true // Helps with Docker compatibility
            },
            cors: {
                origin: '*',
                methods: ['GET', 'POST', 'PUT', 'DELETE'],
                allowedHeaders: ['Content-Type'],
                credentials: true,
            },
            headers: {
                'Access-Control-Allow-Origin': '*', // Allow all origins or specify your frontend domain
                'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers': 'Content-Type, Authorization',
                'Access-Control-Allow-Credentials': 'true'
            }
        },
        build: {
            target: 'esnext',
            manifest: true, // Ensures manifest.json is generated
            outDir: 'public/build', // Specify output directory
            sourcemap: envMode === 'development', // Enable source maps only in development
            minify: envMode === 'production' ? 'esbuild' : false, // Minify for production
            rollupOptions: {
                input: {
                    app: 'resources/js/app.tsx', // Main client-side entry point
                    ssr: 'resources/js/ssr.tsx', // SSR entry point
                },
                output: {
                    entryFileNames: 'assets/app.js', // Single JS output file
                    chunkFileNames: 'assets/[name].js',
                    assetFileNames: 'assets/[name][extname]',
                },
                external: ['http', 'process', 'fs'], // Avoid Rollup errors with built-in modules
            },
        },
        ssr: {
            noExternal: [ // Ensure packages are included in SSR bundle
                '@inertiajs/react',
                '@inertiajs/core',
                'ziggy-js',
            ],
            external: ['http', 'process', 'fs'], // Marks Node.js built-in modules as external
        },
        optimizeDeps: {
            include: [
                '@inertiajs/react',
                '@inertiajs/core',
                'react',
                'react-dom',
                'ziggy-js'
            ],
            exclude: [
                'laravel-vite-plugin' // Laravel plugin should not be optimized
            ],
            entries: ['resources/js/app.tsx']
        },
    };
});
