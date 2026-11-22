#!/usr/bin/env bash
set -euo pipefail
umask 077

stage=${1:?Staged source required}
root=/home/restdevelopments/public_html
manifest="$stage/heritage-manifest.tsv"
test -f "$manifest"

while IFS=$'\t' read -r expected path; do
    [[ "$path" != /* && "$path" != *..* && -f "$stage/$path" ]] || exit 1
    target="$root/$path"
    if [[ "$expected" == NEW ]]; then
        [[ ! -e "$target" ]] || { echo "Unexpected existing file: $path"; exit 1; }
    else
        [[ -f "$target" && "$(git hash-object -- "$target")" == "$expected" ]] || { echo "Live file differs from baseline: $path"; exit 1; }
    fi
    [[ -w "$(dirname "$target")" ]] || { echo "Directory not writable: $path"; exit 1; }
done < "$manifest"

backup=$(mktemp -d "$HOME/restdevelopments-pre-heritage.XXXXXXXX")
awk -F '\t' '$1 != "NEW" {print $2}' "$manifest" > "$backup/existing-files.txt"
tar -czf "$backup/site-files.tar.gz" -C "$root" -T "$backup/existing-files.txt"
cp "$manifest" "$backup/manifest.tsv"

restore_original() {
    tar -xzf "$backup/site-files.tar.gz" -C "$root"
    echo "Deployment failed; original source restored from $backup"
}
trap restore_original ERR

while IFS=$'\t' read -r expected path; do
    target="$root/$path"
    temporary=$(mktemp "$(dirname "$target")/.heritage.XXXXXXXX")
    cat "$stage/$path" > "$temporary"
    if [[ "$expected" == NEW ]]; then chmod 644 "$temporary"; else chmod --reference="$target" "$temporary"; fi
    mv -T -- "$temporary" "$target"
done < "$manifest"

trap - ERR
echo "Heritage theme deployed. Original source backup: $backup/site-files.tar.gz"
