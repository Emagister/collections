#!/bin/bash

calculate_next_version() {
    local increment_type="$1"
    local latest_tag

    latest_tag=$(git describe --tags --abbrev=0 2>/dev/null)

    if [ -z "$latest_tag" ]; then
        echo "1.0.0"
        return 0
    fi

    local current_version_no_v=$(echo "$latest_tag" | sed 's/^v//')

    IFS='.' read -r major minor patch <<< "$current_version_no_v"

    case "$increment_type" in
        major)
            major=$((major + 1))
            minor=0
            patch=0
            ;;
        minor)
            minor=$((minor + 1))
            patch=0
            ;;
        patch)
            patch=$((patch + 1))
            ;;
        *)
            echo "Error: No valid type ('$increment_type'). Available options: major, minor or patch." >&2
            return 1
            ;;
    esac

    echo "$major.$minor.$patch"
}
