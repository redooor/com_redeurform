#!/usr/bin/env bash
set -euo pipefail

VERSION=$(grep -m1 '<version>' redeurform.xml | sed 's|.*<version>\(.*\)</version>.*|\1|' | tr -d ' \t\r\n')
ZIPFILE="com_redeurform-v${VERSION}.zip"

echo "Building ${ZIPFILE}..."
rm -f "${ZIPFILE}"

zip -r "${ZIPFILE}" \
    redeurform.xml \
    README.md \
    LICENSE.txt \
    admin/ \
    site/ \
    language/ \
    media/

echo "Done: ${ZIPFILE} ($(du -sh "${ZIPFILE}" | cut -f1))"
