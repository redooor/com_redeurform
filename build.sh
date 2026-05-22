#!/usr/bin/env bash
set -euo pipefail

VERSION=$(grep -m1 '<version>' redeuform.xml | sed 's|.*<version>\(.*\)</version>.*|\1|' | tr -d ' \t\r\n')
ZIPFILE="com_redeuform-v${VERSION}.zip"

echo "Building ${ZIPFILE}..."
rm -f "${ZIPFILE}"

zip -r "${ZIPFILE}" \
    redeuform.xml \
    README.md \
    LICENSE.txt \
    admin/ \
    site/ \
    language/ \
    media/

echo "Done: ${ZIPFILE} ($(du -sh "${ZIPFILE}" | cut -f1))"
