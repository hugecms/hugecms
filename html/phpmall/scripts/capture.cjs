const { execSync } = require('child_process');
const fs = require('fs');

let browserPath = 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe';
if (!fs.existsSync(browserPath)) {
  browserPath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
}

const outDir = 'C:\\Users\\admin\\.gemini\\antigravity-ide\\brain\\f4ffa360-7b4d-4c3a-83ec-09d5ed442980';
const shot3000 = `${outDir}\\shot_3000.png`;
const shot4173 = `${outDir}\\shot_4173.png`;

console.log('Capturing http://localhost:3000/ ...');
execSync(`"${browserPath}" --headless --disable-gpu --window-size=1440,2400 --screenshot="${shot3000}" "http://localhost:3000/"`);

console.log('Capturing http://localhost:4173/ ...');
execSync(`"${browserPath}" --headless --disable-gpu --window-size=1440,2400 --screenshot="${shot4173}" "http://localhost:4173/"`);

console.log('Done screenshots.');
console.log('shot3000 exists:', fs.existsSync(shot3000));
console.log('shot4173 exists:', fs.existsSync(shot4173));
