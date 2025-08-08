import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    
    // nyalakan kode di bawah kalau mau share demo ke orang lain
    // server: {
    //     host: '192.168.18.146', // ip addr
    //     port: 5173,
    //     cors: {
    //         origin: '*', // Izinkan semua origin
    //     },
    // },

    // server: {
    //     host: '0.0.0.0',
    //     port: 5173,
    //     hmr: {
    //         host: 'cee4-2404-8000-1015-aa0-a2de-189a-f60d-596c.ngrok-free.app',
    //     },
    // },
    // server: {
    //     host: '0.0.0.0',
    //     port: 5173,
    //     https: {
    //         key: fs.readFileSync('./cert/vite.key'),
    //         cert: fs.readFileSync('./cert/vite.crt'),
    //     },
    //     hmr: {
    //         host: 'd55a-2404-8000-1015-aa0-a2de-189a-f60d-596c.ngrok-free.app',
    //         protocol: 'wss',
    //     },
    // },
});
