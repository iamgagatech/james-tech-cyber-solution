import { cp, mkdir, rm } from 'node:fs/promises';

const output = new URL('../deployment/public_html/', import.meta.url);
await rm(output, { recursive: true, force: true });
await mkdir(output, { recursive: true });
await cp(new URL('../dist/', import.meta.url), output, { recursive: true });
console.log('cPanel-ready site copied to deployment/public_html/');
