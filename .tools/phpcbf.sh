#!/bin/bash

# PHP CodeSniffer Beautifier

./vendor/bin/phpcbf --config-set colors 1 >> /dev/null
./vendor/bin/phpcbf --config-set show_progress 1 >> /dev/null
./vendor/bin/phpcbf --config-set show_warnings 1 >> /dev/null
./vendor/bin/phpcbf . -n --runtime-set ignore_warnings_on_exit true --standard=./.tools/rules/phpcs.xml

exit_code=$?

# Check exit code
if [ $exit_code -eq 0 ]; then
    echo "Code Beautifier and Fixer passed with no issues."
elif [ $exit_code -eq 1 ]; then
    echo "Code Beautifier and Fixer found warnings or minor issues."
elif [ $exit_code -eq 2 ]; then
    echo "Code Beautifier and Fixer found errors."
else
    echo "An unexpected error occurred with exit code $exit_code."
fi

# Exit with the Code Beautifier and Fixer exit code to allow CI systems to catch it
exit $exit_code

