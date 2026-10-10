import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
    appId: 'id.bacara.reader',
    appName: 'Bacara',
    webDir: 'mobile-web',
    android: {
        allowMixedContent: false,
    },
};

export default config;
