#!/bin/bash

# PHP CodeSniffer

./vendor/bin/phpcs --config-set colors 1 >> /dev/null
./vendor/bin/phpcs --config-set show_progress 1 >> /dev/null
./vendor/bin/phpcs --config-set show_warnings 1 >> /dev/null
./vendor/bin/phpcs . -n --runtime-set ignore_warnings_on_exit true --standard=./.tools/rules/phpcs.xml

exit_code=$?

# Check exit code
if [ $exit_code -eq 0 ]; then
    echo "CodeSniffer passed with no issues."
elif [ $exit_code -eq 1 ]; then
    echo "CodeSniffer found warnings or minor issues."
elif [ $exit_code -eq 2 ]; then
    echo "CodeSniffer found errors."
else
    echo "An unexpected error occurred with exit code $exit_code."
fi

# Exit with the CodeSniffer exit code to allow CI systems to catch it
exit $exit_code
