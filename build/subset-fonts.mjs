/**
 * Trim the self-hosted fonts to what the site actually uses.
 *
 *   npm i subset-font          (once, anywhere; it is not a project dependency)
 *   node build/subset-fonts.mjs
 *
 * The originals as downloaded sit in build/fonts-full/ and are the input, so this
 * can be re-run after a font update. Output overwrites public/assets/fonts/.
 *
 * Why: the two latin files are on the critical path of every page (they are
 * preloaded in layout.php), and Lighthouse charges their bytes to LCP. Inter goes
 * 47 -> 32 KB by keeping only the weights the CSS asks for (400-900) and the code
 * points inside the latin @font-face range; Anton 18 -> 17 KB.
 *
 * The -ext files are untouched: they cover everything outside that range
 * (Polish, Turkish, the ᴴᴰ superscripts in channel names) and load only when a
 * page actually contains those characters.
 */
import subsetFont from 'subset-font';
import { readFile, writeFile, stat } from 'node:fs/promises';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = dirname(dirname(fileURLToPath(import.meta.url)));
const SRC = join(root, 'build/fonts-full');
const DST = join(root, 'public/assets/fonts');

// the latin unicode-range from site.css, plus the few marks the UI draws
const chars = (() => {
  let s = '';
  for (let c = 0x20; c <= 0xff; c++) s += String.fromCodePoint(c);          // ASCII + Latin-1 (NL/FR accents)
  for (const cp of [0x131, 0x152, 0x153, 0x2bb, 0x2bc, 0x2c6, 0x2da, 0x2dc, 0x304, 0x308, 0x329,
                    0x20ac, 0x2122, 0x2191, 0x2193, 0x2212, 0x2215, 0xfffd]) s += String.fromCodePoint(cp);
  for (let c = 0x2000; c <= 0x206f; c++) s += String.fromCodePoint(c);      // spaces, dashes, quotes, ellipsis
  s += '✓✕✗★☆♪→←·•';
  return s;
})();

for (const { file, axes } of [
  { file: 'inter-latin-wght.woff2', axes: { wght: { min: 400, max: 900 } } },
  { file: 'anton-latin-400.woff2', axes: undefined },
]) {
  const src = await readFile(join(SRC, file));
  const out = await subsetFont(src, chars, { targetFormat: 'woff2', variationAxes: axes });
  await writeFile(join(DST, file), out);
  console.log(`${file}: ${((await stat(join(SRC, file))).size / 1024).toFixed(1)} KB -> ${(out.length / 1024).toFixed(1)} KB`);
}
