import { cp, mkdir, rm, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const webDirectory = resolve(projectRoot, 'mobile-web');
const androidDirectory = resolve(projectRoot, 'android');
const assetsDirectory = resolve(androidDirectory, 'app', 'src', 'main', 'assets');
const publicDirectory = resolve(assetsDirectory, 'public');
const configDirectory = resolve(androidDirectory, 'app', 'src', 'main', 'res', 'xml');

await rm(publicDirectory, { recursive: true, force: true });
await mkdir(assetsDirectory, { recursive: true });
await cp(webDirectory, publicDirectory, { recursive: true });

await writeFile(
    resolve(assetsDirectory, 'capacitor.config.json'),
    `${JSON.stringify({
        appId: 'id.bacara.reader',
        appName: 'Bacara',
        webDir: 'mobile-web',
        android: { allowMixedContent: false },
    }, null, 2)}\n`,
);
await writeFile(resolve(assetsDirectory, 'capacitor.plugins.json'), '[]\n');
await writeFile(resolve(publicDirectory, 'cordova.js'), '');
await writeFile(resolve(publicDirectory, 'cordova_plugins.js'), '');

await mkdir(configDirectory, { recursive: true });
await writeFile(
    resolve(configDirectory, 'config.xml'),
    "<?xml version='1.0' encoding='utf-8'?>\n<widget version=\"1.0.0\" xmlns=\"http://www.w3.org/ns/widgets\"><access origin=\"*\" /></widget>\n",
);

console.log('Android assets synchronized.');
