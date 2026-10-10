import { cp, mkdir, rm, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const webDirectory = resolve(projectRoot, 'mobile-web');
const androidDirectory = resolve(projectRoot, 'android');
const assetsDirectory = resolve(androidDirectory, 'app', 'src', 'main', 'assets');
const publicDirectory = resolve(assetsDirectory, 'public');
const configDirectory = resolve(androidDirectory, 'app', 'src', 'main', 'res', 'xml');
const cordovaDirectory = resolve(androidDirectory, 'capacitor-cordova-android-plugins');

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

// Capacitor membuat modul ini ketika menjalankan `cap sync`. Modul tersebut
// diabaikan Git agar plugin Cordova tidak menjadi artefak sumber, maka buat
// ulang versi kosongnya untuk proyek yang belum memakai plugin Cordova.
await mkdir(resolve(cordovaDirectory, 'src', 'main'), { recursive: true });
await writeFile(
    resolve(cordovaDirectory, 'cordova.variables.gradle'),
    [
        '// Generated Cordova compatibility configuration.',
        'ext {',
        "    cdvMinSdkVersion = project.hasProperty('minSdkVersion') ? rootProject.ext.minSdkVersion : 24",
        '    cdvPluginPostBuildExtras = []',
        '    cordovaConfig = [:]',
        '}',
        '',
    ].join('\n'),
);
await writeFile(
    resolve(cordovaDirectory, 'build.gradle'),
    [
        "apply plugin: 'com.android.library'",
        "apply from: 'cordova.variables.gradle'",
        '',
        'android {',
        '    namespace = "capacitor.cordova.android.plugins"',
        '    compileSdk = rootProject.ext.compileSdkVersion',
        '    defaultConfig {',
        '        minSdkVersion rootProject.ext.minSdkVersion',
        '        targetSdkVersion rootProject.ext.targetSdkVersion',
        '    }',
        '    compileOptions {',
        '        sourceCompatibility JavaVersion.VERSION_21',
        '        targetCompatibility JavaVersion.VERSION_21',
        '    }',
        '}',
        '',
        'repositories {',
        '    google()',
        '    mavenCentral()',
        '}',
        '',
        'dependencies {',
        '    implementation fileTree(dir: "src/main/libs", include: ["*.jar"])',
        '    implementation "androidx.appcompat:appcompat:$androidxAppCompatVersion"',
        '    implementation "org.apache.cordova:framework:$cordovaAndroidVersion"',
        '}',
        '',
        'for (def func : cdvPluginPostBuildExtras) {',
        '    func()',
        '}',
        '',
    ].join('\n'),
);
await writeFile(
    resolve(cordovaDirectory, 'src', 'main', 'AndroidManifest.xml'),
    "<?xml version='1.0' encoding='utf-8'?>\n<manifest xmlns:android=\"http://schemas.android.com/apk/res/android\" />\n",
);

console.log('Android assets synchronized.');
