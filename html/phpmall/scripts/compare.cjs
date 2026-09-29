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
  const p3000 = await fetchUrl('http://localhost:3000/');
  const p4173 = await fetchUrl('http://localhost:4173/');
  
  const outDir = 'C:\\Users\\admin\\.gemini\\antigravity-ide\\brain\\f4ffa360-7b4d-4c3a-83ec-09d5ed442980\\scratch';
  if (!fs.existsSync(outDir)) {
    fs.mkdirSync(outDir, { recursive: true });
  }
  fs.writeFileSync(path.join(outDir, 'p3000.html'), p3000);
  fs.writeFileSync(path.join(outDir, 'p4173.html'), p4173);

  console.log('Saved p3000.html and p4173.html');
  console.log('p3000 length:', p3000.length);
  console.log('p4173 length:', p4173.length);
}

run();
