#!/bin/bash
if php -v > /dev/null 2>&1; then
  exit 0
else
  exit 1
fi