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

    // MSISDN not authorized
    //public static final Integer MSISDN_NOT_AUTHORIZED = 63;

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
}