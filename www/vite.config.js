import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import * as fs from "node:fs";


export default defineConfig(({ mode }) => {
    // Load environment variables based on the current mode (development, production, etc.)
    const env = loadEnv(mode, process.cwd(), '');
    const date = new Date();

    console.log('DOMAIN_NAME:',  env.SERVER_DOMAIN);
    console.log('Date:', `${date.toLocaleDateString()}, ${date.toLocaleTimeString()}`);

    return {
        plugins: [
            laravel({
                input: 'resources/js/app.tsx',
                ssr: 'resources/js/ssr.tsx',
                refresh: true,
            }),
            react(),
        ],
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
                usePolling: true
            },
            cors: {
                origin: env.APP_URL
            },
        },
    };
});
