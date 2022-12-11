#!/usr/bin/env bash

# https://community.sonarsource.com/t/code-coverage-doesnt-work-with-github-action/16747/5
echo "fix code coverage paths"
sed -i 's@'$GITHUB_WORKSPACE'/@/github/workspace/@g' coverage.xml
sed -i 's@'$GITHUB_WORKSPACE'/@@g' test-report.xml

#php scripts/fix_report.php
