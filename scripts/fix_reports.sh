#!/usr/bin/env bash

# https://community.sonarsource.com/t/code-coverage-doesnt-work-with-github-action/16747/5
echo "fix code coverage paths"
sed -i 's/\/home\/runner\/work\/daux.io\/daux.io\//\/github\/workspace\//g' coverage.clover

php scripts/fix_report.php
