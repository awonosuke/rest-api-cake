<?php
declare(strict_types=1);

// https://developer.mozilla.org/ja/docs/Web/HTTP/Status

// OK
const StatusOK = 200;

// Client error
const StatusBadRequest = 400;
const StatusUnauthorized = 401;
const StatusForbidden = 403;
const StatusNotFound = 404;
const StatusMethodNotAllowed = 405;
const StatusUnprocessableEntity = 422;

// Server error
const StatusInternalServerError = 500;
const StatusNotImplemented = 501;
const StatusBadGateway = 502;
const StatusServerUnavailable = 503;

return array();
