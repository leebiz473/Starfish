#!/bin/bash
# Custom pre-push script

EXIT_CODE=0

chmod +x ./.deploy/*.sh

# PHPUnit
./.deploy/phpunit.sh || EXIT_CODE=1

[ $EXIT_CODE -ne 0 ] &&
echo -e "\e[31m\n\t************************************************\n\t*  PRE-PUSH HOOK FAILED (see reasons above)  *\n\t************************************************" ||
echo -e "\e[32m\n\t*********************************************\n\t*  PRE-PUSH HOOK PASSED, Code pushed :) *\n\t*********************************************"
echo -e "\033[0m"
exit $EXIT_CODE
