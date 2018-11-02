#!/usr/bin/env node
"use strict";

const shell = require('shelljs'),
  path = require('path'),
  got = require('got');

console.log('Fetching Git commit hash...');

const gitCommitRet = shell.exec('git rev-parse HEAD', {
  cwd: path.join(__dirname, '..')
});

if (0 !== gitCommitRet.code) {
  console.error('Error getting git commit hash');
  process.exit(-1);
}

const gitCommitHash = gitCommitRet.stdout.trim();

console.log(`Git commit: ${gitCommitHash}`);
console.log('Calling Travis...');

var options = {
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Travis-API-Version": "3",
    "Authorization": `token ${process.env.TRAVIS_API_TOKEN}`,
  },
  body: JSON.stringify({
    request: {
      message: `Trigger build of PWA Showcase from theme update: ${gitCommitHash}`,
      branch: "master",
    },    
  }),
};

// Deploy Heavy.
got.post('https://api.travis-ci.com/repo/xwp%2Fshowcase-pwa-pantheon/requests', options )
.then(() => {
  console.log('Triggered build of xwp/showcase-pwa-pantheon');
})
.catch((err) => {
  console.error(err);
  process.exit(-1);
});
