const http = require('http');
const fs = require('fs');
const path = require('path');

function fetchUrl(url) {
  return new Promise((resolve, reject) => {
    http.get(url, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => resolve(data));
    }).on('error', reject);
  });
}

async function run() {
  const p4173 = await fetchUrl('http://localhost:4173/');
  const matches = p4173.match(/href="(\/assets\/[^"]+\.css)"/g) || [];
  for (const m of matches) {
    const cssPath = m.match(/href="([^"]+)"/)[1];
    const cssContent = await fetchUrl('http://localhost:4173' + cssPath);
    console.log(`\n=== CSS File: ${cssPath} (Length: ${cssContent.length}) ===`);
    console.log(cssContent.slice(0, 300));
    console.log('...');
    console.log(cssContent.slice(-300));
  }
}

run();
