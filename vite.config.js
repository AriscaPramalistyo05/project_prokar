import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import os from 'os';

function getNetworkIp() {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal) {
                return iface.address;
            }
        }
    }
    return '127.0.0.1';
}

export default defineConfig({
    server: {
        host: 'localhost',
        port: 5173,
        cors: true,
    },
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Admin panel — Tailwind v4 + daisyUI, terpisah dari frontend (CDN)
                'resources/css/admin.css',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        // Minifikasi CSS lebih agresif
        cssMinify: true,
        // Pisahkan chunk agar cache lebih efektif
        rollupOptions: {
            output: {
                // Pisahkan vendor libraries dari app code
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
                // Optimasi nama file
                assetFileNames: 'assets/[name]-[hash][extname]',
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
            },
        },
        // Hapus console.log di production
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        // Kurangi chunk size warning threshold
        chunkSizeWarningLimit: 500,
    },
});
