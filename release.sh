#!/bin/bash

set -e

if [ $# -ne 1 ]; then
  echo "Usage: `basename $0` <tag>"
  exit 65
fi

TAG=$1

# Tag & build master branch
git checkout master
git tag ${TAG}
box build

# Copy executable file into GH pages
git checkout gh-pages

cp probuild.phar downloads/probuild-${TAG}.phar
git add downloads/probuild-${TAG}.phar

SHA1=$(openssl sha1 probuild.phar)

JSON='name:"probuild.phar"'
JSON="${JSON},sha1:\"${SHA1}\""
JSON="${JSON},url:\"http://cristian-quiroz.github.io/probuild/downloads/probuild-${TAG}.phar\""
JSON="${JSON},version:\"${TAG}\""

if [ -f probuild.phar.pubkey ]; then
    cp probuild.phar.pubkey pubkeys/probuild-${TAG}.phar.pubkeys
    git add pubkeys/probuild-${TAG}.phar.pubkeys
    JSON="${JSON},publicKey:\"http://cristian-quiroz.github.io/probuild/pubkeys/probuild-${TAG}.phar.pubkey\""
fi

# Update manifest
cat manifest.json | jsawk -a "this.push({${JSON}})" | python -mjson.tool > manifest.json.tmp
mv manifest.json.tmp manifest.json
git add manifest.json

git commit -m "Bump version ${TAG}"

# Go back to master
git checkout master

git push origin gh-pages
git push ${TAG}

echo "Done."
#
