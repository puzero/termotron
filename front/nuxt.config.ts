export default defineNuxtConfig({
  ssr: false,

  typescript: {
    shim: false,
  },

  app: {
    head: {
      title:
        "Термотрон",
    },
  },

  build: {
    transpile: ["vuetify"],
  },

  nitro: {
    serveStatic: true,
  },

  sourcemap: { server: false, client: false },
  devServerHandlers: [],
  compatibilityDate: "2025-04-04",

  runtimeConfig: {
    public: {
      backendUrl: process.env.NUXT_PUBLIC_BACKEND_URL
    }
  }
});