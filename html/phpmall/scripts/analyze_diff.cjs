const fs = require('fs');
const path = require('path');

const outDir = 'C:\\Users\\admin\\.gemini\\antigravity-ide\\brain\\f4ffa360-7b4d-4c3a-83ec-09d5ed442980\\scratch';
const p3000 = fs.readFileSync(path.join(outDir, 'p3000.html'), 'utf-8');
const p4173 = fs.readFileSync(path.join(outDir, 'p4173.html'), 'utf-8');

console.log('=== CSS Links in 3000 ===');
const css3000 = p3000.match(/<link[^>]+rel=["']stylesheet["'][^>]*>/gi) || [];
console.log(css3000);

console.log('=== CSS Links in 4173 ===');
const css4173 = p4173.match(/<link[^>]+rel=["']stylesheet["'][^>]*>/gi) || [];
console.log(css4173);

// 提取主要 section 类名
function extractSections(html) {
  const matches = html.match(/<(div|header|main|section|aside|footer)[^>]+class=["']([^"']+)["']/gi) || [];
  return matches.slice(0, 30).map(m => m.trim());
}

console.log('\n=== Top 20 Classes in 3000 ===');
console.log(extractSections(p3000).slice(0, 20));

console.log('\n=== Top 20 Classes in 4173 ===');
console.log(extractSections(p4173).slice(0, 20));
