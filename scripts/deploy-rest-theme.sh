#!/usr/bin/env bash
# Publishes the staged theme files listed in rest-manifest.tsv.
# Existing files must match the expected baseline hash; new files must not exist yet.
# A backup of every replaced file is kept, and restored automatically on failure.
set -euo pipefail
umask 022

stage=${1:?Staged source required}
root=/home/restdevelopments/public_html
manifest="$stage/rest-manifest.tsv"
test -f "$manifest"

while IFS=$'\t' read -r expected path; do
    [[ "$path" != /* && "$path" != *..* && -f "$stage/$path" ]] || { echo "Bad path: $path"; exit 1; }
    target="$root/$path"
    if [[ "$expected" == NEW ]]; then
        [[ ! -e "$target" ]] || { echo "Unexpected existing file: $path"; exit 1; }
    else
        [[ -f "$target" && "$(git hash-object -- "$target")" == "$expected" ]] || { echo "Live file differs from baseline: $path"; exit 1; }
    fi
done < "$manifest"

backup=$(mktemp -d "$HOME/restdevelopments-pre-rest-theme.XXXXXXXX")
awk -F '\t' '$1 != "NEW" {print $2}' "$manifest" > "$backup/existing-files.txt"
awk -F '\t' '$1 == "NEW" {print $2}' "$manifest" > "$backup/new-files.txt"
tar -czf "$backup/site-files.tar.gz" -C "$root" -T "$backup/existing-files.txt"
cp "$manifest" "$backup/manifest.tsv"

restore_original() {
    tar -xzf "$backup/site-files.tar.gz" -C "$root"
    while read -r path; do rm -f -- "$root/$path"; done < "$backup/new-files.txt"
    echo "Deployment failed; original source restored from $backup"
}
trap restore_original ERR

while IFS=$'\t' read -r expected path; do
    target="$root/$path"
    mkdir -p -- "$(dirname "$target")"
    temporary=$(mktemp "$(dirname "$target")/.rest-theme.XXXXXXXX")
    cat "$stage/$path" > "$temporary"
    if [[ "$expected" == NEW ]]; then chmod 644 "$temporary"; else chmod --reference="$target" "$temporary"; fi
    mv -T -- "$temporary" "$target"
done < "$manifest"

trap - ERR
(cd "$root" && php artisan view:clear >/dev/null 2>&1) || true
echo "Rest theme deployed. Backup: $backup/site-files.tar.gz (new files listed in $backup/new-files.txt)"
