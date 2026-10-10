import { cp, mkdir, rm } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const source = resolve(projectRoot, 'resources', 'views', 'bacara.blade.php');
const webDirectory = resolve(projectRoot, 'mobile-web');
const destination = resolve(webDirectory, 'index.html');

await rm(webDirectory, { recursive: true, force: true });
await mkdir(webDirectory, { recursive: true });
await cp(source, destination);

console.log('Mobile web assets prepared in mobile-web/.');
