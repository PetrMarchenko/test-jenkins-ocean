<?php
function checkService() {
    return true;
}

if (!checkService()) {
    echo "Service is down.\n";
    exit(1);
} else {
    echo "Service is running normally.\n";
    exit(0);
}

