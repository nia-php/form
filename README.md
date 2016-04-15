# nia - Form

The form component is used to validate data against the field definitions of a form.

## Installation

Require this package with Composer.

```bash
    composer require nia/form
```

## Tests
To run the unit test use the following command:

    $ cd /path/to/nia/component/
    $ phpunit --bootstrap=vendor/autoload.php tests/

## How to use
The following sample shows you how to use form component in a simple contact form.

```php
    /**
     * Simple contact form validator implementation.
     */
    class ContactForm implements FormInterface
    {
        use FormTrait;

        /**
         * Constructor.
         */
        public function __construct()
        {
            // Allowed salutations: Mr, Mrs, Ms
            $this->addField('salutation', new InSetValidator([
                'Mr',
                'Mrs',
                'Ms'
            ]));

            // Name must be between 4 and 64 characters.
            $this->addField('name', new LengthValidator(4, 64), new TrimSanitizer());

            // Company is optional.
            $this->addField('company', new NullValidator(), new TrimSanitizer());

            // Email address needs to be well formed.
            $this->addField('email', new EmailAddressValidator(), new TrimSanitizer());

            // Message must be between 10 and 1024 characters.
            $this->addField('message', new LengthValidator(10, 1024), new TrimSanitizer());
        }
    }

    // [...]

    $form = new ContactForm();

    // This map will be filled with received, sanitized and validated data.
    $context = new Map();

    // Data to validate. Potential source: Nia\RequestResponse\RequestInterface implementations.
    $data = new Map([
        'salutation' => 'Mr',
        'name' => '  John Doe ',
        'company' => '',
        'email' => 'john.doe@my-domain.tld',
        'message' => ' Hello, this is John Doe! How are you??????      '
    ]);

    // Validate the data and fill up the context.
    $violations = $form->validate($data, $context);

    // Check whether the data is valid.
    if (count($violations) === 0) {
        // Okay, data valid so send the message via email or write into database.
    }

    // $context now looks like:
    //
    //    object(Nia\Collection\Map\StringMap\Map)#0 (1) {
    //      ["values":"Nia\Collection\Map\StringMap\Map":private]=>
    //      array(5) {
    //        ["salutation"]=>
    //        string(2) "Mr"
    //        ["name"]=>
    //        string(8) "John Doe"
    //        ["company"]=>
    //        string(0) ""
    //        ["email"]=>
    //        string(22) "john.doe@my-domain.tld"
    //        ["message"]=>
    //        string(42) "Hello, this is John Doe! How are you??????"
    //      }
    //    }

```
