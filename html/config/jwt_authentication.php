<?php
declare(strict_types=1);

// JWT authentication constant
const JWT_ALG = 'RS256';
const JWT_EXPIRES = 3600; // 1 hour

// RSA KEY PATH
const PUBLIC_KEY_PATH = CONFIG . '/jwt.pem';
const PRIVATE_KEY_PATH = CONFIG . '/jwt.key';

return array();
