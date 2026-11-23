import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'io.ionic.starter',
  appName: 'apk-kurir-ekspedisi',
  webDir: 'www',
  plugins: {
    // Gunakan HTTP native Android agar permintaan ke server Laravel lokal tidak
    // terhalang kebijakan CORS WebView atau konten campuran.
    CapacitorHttp: {
      enabled: true,
    },
  },
  server: {
    cleartext: true,
    // Host API dimasukkan pengguna saat runtime, jadi tidak diikat ke IP LAN tertentu.
  },
};

export default config;
