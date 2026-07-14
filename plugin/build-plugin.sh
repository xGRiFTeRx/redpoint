#!/bin/bash
#
# Package the widgets plugin for upload to staging.
#
#   bash plugin/build-plugin.sh
#
# Produces plugin/dist/redpoint-widgets-<VERSION>.zip. The version is read from the plugin
# header, so bump it there and nowhere else.
#
# Built with PHP's ZipArchive, deliberately.
#
# PowerShell's Compress-Archive writes WINDOWS path separators into the archive
# ("redpoint-widgets\assets\"), and it adds bare directory entries. The ZIP spec requires
# forward slashes, so WordPress's unzipper fails with:
#
#     Could not copy file. redpoint-widgets\assets\
#
# ZipArchive writes "redpoint-widgets/assets/css/x.css" — which is exactly what the
# working Espressimo dist zips contain. Git Bash on Windows ships no `zip` binary, so PHP
# is the tool that is actually here.
#
set -euo pipefail

HERE="$(cd "$(dirname "$0")" && pwd)"
SRC="${HERE}/redpoint-widgets"
DIST="${HERE}/dist"
PHP="/c/xampp/php/php.exe"

VERSION="$(grep -m1 '^Version:' "${SRC}/redpoint-widgets.php" | sed 's/Version:[[:space:]]*//' | tr -d '\r')"
[ -n "$VERSION" ] || { echo "could not read Version from the plugin header"; exit 1; }

ZIP="${DIST}/redpoint-widgets-${VERSION}.zip"
mkdir -p "$DIST"

# Refuse to overwrite an existing version.
#
# The 1.0.0 zip got rebuilt three times and ended up containing three widgets while still
# claiming to be the one-widget build — and staging would have refused it anyway, since
# WordPress will not reinstall a plugin at a version it already has. A forgotten bump must
# fail here, loudly, not silently ship the wrong contents.
#
# Pass --force to rebuild the same version deliberately (e.g. re-zipping after a bad build).
if [ -f "$ZIP" ] && [ "${1:-}" != "--force" ]; then
	echo "redpoint-widgets-${VERSION}.zip already exists."
	echo
	echo "Bump 'Version:' in redpoint-widgets/redpoint-widgets.php and note the change in"
	echo "CHANGELOG.md — a new widget bumps the minor, a fix bumps the patch."
	echo
	echo "Or re-zip this same version on purpose:  bash plugin/build-plugin.sh --force"
	exit 1
fi
rm -f "$ZIP"

"$PHP" -r '
$src = $argv[1];
$zip = $argv[2];
$root = "redpoint-widgets";

$z = new ZipArchive();
if ( $z->open( $zip, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
    fwrite( STDERR, "could not create $zip\n" );
    exit( 1 );
}

$skip = [ "desktop.ini", ".DS_Store", "Thumbs.db" ];
$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator( $src, FilesystemIterator::SKIP_DOTS )
);

$n = 0;
foreach ( $it as $file ) {
    if ( ! $file->isFile() || in_array( $file->getFilename(), $skip, true ) ) {
        continue;
    }
    // Relative path, forward slashes — never the platform separator.
    $rel = substr( $file->getPathname(), strlen( $src ) + 1 );
    $rel = str_replace( DIRECTORY_SEPARATOR, "/", $rel );
    $z->addFile( $file->getPathname(), $root . "/" . $rel );
    $n++;
}
$z->close();
echo "$n files\n";
' "$(cygpath -w "$SRC")" "$(cygpath -w "$ZIP")"

echo "built: $ZIP"
echo

# Read the archive back and prove the separators are right — this is the bug that shipped
# once already, so verify rather than assume.
"$PHP" -r '
$z = new ZipArchive();
$z->open( $argv[1] );
$bad = 0;
for ( $i = 0; $i < $z->numFiles; $i++ ) {
    $name = $z->getNameIndex( $i );
    if ( strpos( $name, "\\" ) !== false ) { $bad++; }
    echo "  " . $name . "\n";
}
echo $bad ? "\n  FAIL: $bad entries use backslashes — WordPress will refuse this\n"
          : "\n  ok: all entries use forward slashes\n";
' "$(cygpath -w "$ZIP")"
