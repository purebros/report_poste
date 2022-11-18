<?php

namespace App\Utils;


class Constants {
    // Operation executed successfully
    const SUCCESS = 0;

    // Invalid Username/Password
    const BAD_CREDENTIALS = 1;

    // Connection not allowed from source address
    const CONNECTION_NOT_ALLOWED = 2;

    // Permission denied to use this operation
    const PERMISSION_DENIED = 3;

    // MT.Body is out of MT.Body Count range
    const BODY_IS_OUT_OF_RANGE = 12;

    // ServiceType not set to the requested operation. Contact Pure Bros support
    const SERV_TYPE_NOT_SET = 13;

    // Invalid content source
    const INVALID_CONTENT_SOURCE = 14;

    // Target phone number not covered by Purebros
    const MSISDN_NOT_COVERED = 21;

    // Unauthorized Carrier
    const UNAUTHORIZED_CARRIER = 22;

    // Target phone is blocked by blacklist
    const MSISDN_IN_BLACK_LIST = 24;

    //  MSISDN not authorized
    const MSISDN_NOT_AUTHORIZED = 25;

    // Invalid target on carrier
    const INVALID_TARGET_ON_CARRIER = 26;

    // MSISDN sim is child
    const MSISDN_SIM_IS_CHILD = 28;

    // Problems connecting to carrier
    const CARRIER_CONNECTING_ERROR = 32;

    // Content not delivered : MT.ContentID already exists
    const MT_ID_ALREADY_EXISTED = 34;

    // Error retryable
    const RETRYABLE_ERROR = 35;

    // Operation denied for carrier
    const CARRIER_OPERATION_DENIED = 36;

    // Invalid operation/Service disable (Just for activation)
    const INVALID_OPERATION = 37;

    // MSISDN deleted
    const MSISDN_DELETED = 44;

    // The maximum service amount was already reached
    const AMOUNT_REACHED = 51;

    // The maximum charge retries was already reached
    const RETRIES_REACHED = 53;

    // The maximum monthly cap was reached
    const MONTHLY_CAP_REACHED = 58;

    // The TARGET is already active
    const MSISDN_IS_ACTIVE = 60;

    // Service not found
    const SERVICE_NOT_FOUND = 64;

    // The TARGET is already deactivated
    const MSISDN_NOT_ACTIVE = 65;

    // System closed
    const SYSTEM_CLOSED = 91;

    // Browsing timeout
    const BROWSING_TIMEOUT = 92;

    // Internal database access error
    const DB_ERROR = 96;

    // Timeout error
    const TIMEOUT_ERROR = 97;

    // Error processing the request: invalid parameter(s)
    const INVALID_PARAMETERS = 98;

    // Internal error processing the request
    const INTERNAL_ERROR = 99;

    const TEXT_ERROR = [
        self::SUCCESS => 'Operation executed successfully',
        self::BAD_CREDENTIALS => 'Invalid Username/Password',
        self::CONNECTION_NOT_ALLOWED => 'Connection not allowed from source address',
        self::PERMISSION_DENIED => 'Permission denied to use this operation',
        self::BODY_IS_OUT_OF_RANGE => 'MT.Body is out of MT.Body Count range',
        self::SERV_TYPE_NOT_SET => 'ServiceType not set to the requested operation. Contact Pure Bros support',
        self::INVALID_CONTENT_SOURCE => 'Invalid content source',
        self::MSISDN_NOT_COVERED => 'Target phone number not covered by Purebros',
        self::UNAUTHORIZED_CARRIER => 'Unauthorized Carrier',
        self::MSISDN_IN_BLACK_LIST => 'Target phone is blocked by blacklist',
        self::MSISDN_NOT_AUTHORIZED => 'MSISDN not authorized',
        self::INVALID_TARGET_ON_CARRIER => 'Invalid target on carrier',
        self::MSISDN_SIM_IS_CHILD => 'MSISDN sim is child',
        self::CARRIER_CONNECTING_ERROR => 'Problems connecting to carrier',
        self::MT_ID_ALREADY_EXISTED => 'Content not delivered : MT.ContentID already exists',
        self::RETRYABLE_ERROR => 'Error retryable',
        self::CARRIER_OPERATION_DENIED => 'Operation denied for carrier',
        self::INVALID_OPERATION => 'Invalid operation/Service disable (Just for activation)',
        self::MSISDN_DELETED => 'MSISDN deleted',
        self::AMOUNT_REACHED => 'The maximum service amount was already reached',
        self::RETRIES_REACHED => 'The maximum charge retries was already reached',
        self::MONTHLY_CAP_REACHED => 'The maximum monthly cap was reached',
        self::MSISDN_IS_ACTIVE => 'The TARGET is already active',
        self::SERVICE_NOT_FOUND => 'Service not found',
        self::MSISDN_NOT_ACTIVE => 'The TARGET is already deactivated',
        self::SYSTEM_CLOSED => 'System closed',
        self::BROWSING_TIMEOUT => 'Browsing timeout',
        self::DB_ERROR => 'Internal database access error',
        self::TIMEOUT_ERROR => 'Timeout error',
        self::INVALID_PARAMETERS => 'Error processing the request: invalid parameter(s)',
        self::INTERNAL_ERROR => 'Internal error processing the request',
    ];
}
