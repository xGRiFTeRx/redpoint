#!/bin/bash
#
# Package the widgets plugin for upload to staging.
#
#   bash wordpress/build-plugin.sh
#
# Produces wordpress/dist/redpoint-widgets-<VERSION>.zip. The version is read from the
# plugin header, so bump it there and nowhere else.
#
# The archive contains a single top-level redpoint-widgets/ folder — that is what
# WordPress's Plugins > Add New > Upload Plugin expects, and it is how the Espressimo dist
# zips are laid out.
#
set -euo pipefail

HERE="$(cd "$(dirname "$0")" && pwd)"
SRC="${HERE}/redpoint-widgets"
DIST="${HERE}/dist"

VERSION="$(grep -m1 '^Version:' "${SRC}/redpoint-widgets.php" | sed 's/Version:[[:space:]]*//' | tr -d '\r')"
[ -n "$VERSION" ] || { echo "could not read Version from the plugin header"; exit 1; }

ZIP="${DIST}/redpoint-widgets-${VERSION}.zip"
mkdir -p "$DIST"
rm -f "$ZIP"

# Git Bash on Windows ships no `zip`, so use PowerShell's Compress-Archive. Staged into a
# temp folder first, because Compress-Archive has no exclude option — and desktop.ini,
# which Windows sprinkles into folders, has no business in a plugin zip.
STAGE="$(mktemp -d)"
mkdir -p "${STAGE}/redpoint-widgets"
( cd "$SRC" && find . -type f \
    ! -name 'desktop.ini' ! -name '.DS_Store' ! -name 'Thumbs.db' \
    -exec cp --parents {} "${STAGE}/redpoint-widgets/" \; )

powershell.exe -NoProfile -Command \
  "Compress-Archive -Path '$(cygpath -w "${STAGE}/redpoint-widgets")' -DestinationPath '$(cygpath -w "$ZIP")' -Force" >/dev/null

rm -rf "$STAGE"

echo "built: $ZIP"
echo
powershell.exe -NoProfile -Command \
  "Add-Type -A System.IO.Compression.FileSystem; [IO.Compression.ZipFile]::OpenRead('$(cygpath -w "$ZIP")').Entries | ForEach-Object { '  ' + \$_.FullName }"
