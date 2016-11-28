# easy_mailinator

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/brnlbs/mailinator/blob/master/LICENSE)

PHP wrapper library for the Mailinator API

## API Token
To obtain an API token for Mailinator, you must first create a [Mailinator](http://www.mailinator.com) account. API access then can be obtained by purchasing an upgrade plan [here](https://www.mailinator.com/featurematrix.jsp) and then obtain your API token at [https://www.mailinator.com/settings.jsp](https://www.mailinator.com/settings.jsp)

## Requirements
In order to use this, you must have the [cURL](http://php.net/manual/en/book.curl.php) extension installed on your server. [PHP](http://www.php.net) 5.5 or higher recommended.

## Installation
`composer require cnorton-webdev/easy-mailinator`

## Public inbox example usage
```php
$token = 'your_token_goes_here';

$mail = new easy_mailinator($token);

// Retrieve messages for an inbox

$name = 'some_name_here';

$messages = $mail->inbox($name);

// Show message count - MUST be called AFTER getting messages otherwise will return 0

$message_count = $mail->get_mail_count();

// Get message content

$message = $mail->get($msg_id);

// Delete a message

$is_deleted = $mail->delete($msg_id);

// Retrieve saved messages

$saved_messages = $mail->saved();

// Get saved messages count - MUST be called AFTER getting messages otherwise will return 0

$saved_count = $mail->get_saved_count();
```

## Private domain example usage
```php
$token = 'your_token_goes_here';

$mail = new easy_mailinator($token, true);

// Retrieve messages for private domain inbox

$messages = $mail->private_domain();

// Show private message count - MUST be called AFTER getting messages

$private_message_count = $mail->get_private_count();

// Retrieve and delete messages the same as public inbox example
```
