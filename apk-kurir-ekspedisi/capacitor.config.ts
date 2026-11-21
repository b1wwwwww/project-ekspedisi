import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'io.ionic.starter',
  appName: 'apk-kurir-ekspedisi',
  webDir: 'www',
  plugins: {
    // Use the native Android HTTP stack so requests to the local Laravel server
    // are not blocked by WebView CORS or mixed-content behavior.
    CapacitorHttp: {
      enabled: true,
    },
  },
  server: {
    cleartext: true,
    allowNavigation: ['192.168.1.10', '192.168.1.10:8000'],
  },
};

export default config;
