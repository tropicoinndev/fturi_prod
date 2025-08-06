import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import dotenv from 'dotenv';
dotenv.config();

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/components/progreso.vue'],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
    },
  },
  server: {
    watch: {
      usePolling: true,
      interval: 100,
    },
    host: true,
    hmr: {
      host: process.env.PUSHER_HOST,
    },
    port: 4000,
    hot: true,
  },
});
