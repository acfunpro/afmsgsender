# afmsgsender

## Example

``` php

$cSendService = new SendService( );

/**
 * -- send mobile message
 * 
 * - $mobile    string     // mobile
 * - $code      string     // verifycode
 * - $type      string     // message template type '100001','100002'...
 * - $apikey    string     // verify the request is valid
 *
 * - return     vdata
 */
$cSendService->message($mobile, $code, $type, $apikey);

/**
 * -- send email message
 * 
 * - $email         string     // email address
 * - $username      string     // to whom
 * - $type          string     // email template type '100001','100002'...
 *
 * - return         vdata
 */
$cSendService->email($email, $username, $type);

/**
 * -- set the request service url
 *
 * - $url   string      // service url
 *
 * - return  boolean
 */
$cSendService->setServiceUrl($url)

/**
 * -- set the request timeout
 *
 * - $timeout   number  //  timeout
 *
 * - return  boolean
 */
$cSendService->setSetviceTimeOut($timeout)

```

## Message Template Type
* *100001*  **user register**
* *100003*  **user reset password**




## Email Template Type
* *100001*  **user register**
* *100002*  **user bind email**
* *100003*  **user reset password**


Message sender by Acfun, Inc.
