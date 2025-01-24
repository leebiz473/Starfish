#!/bin/bash

# PHP Mess Detector

./vendor/bin/phpmd src ansi ./.tools/rules/phpmd.xml --color

exit_code=$?

# Check exit code
if [ $exit_code -eq 0 ]; then
    echo "Mess Detector check passed"
elif [ $exit_code -eq 1 ]; then
    echo "Mess Detector found warnings or minor issues."
elif [ $exit_code -eq 2 ]; then
    echo "Mess Detector found errors."
else
    echo "An unexpected error occurred with exit code $exit_code."
fi

exit $exit_code

