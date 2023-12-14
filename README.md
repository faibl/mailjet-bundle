# MailjetBundle
Symfony bundle for MailJet Integration

## Install the package with:
```
composer require faibl/mailjet-bundle
```

## Configure one or multiple accounts
### Minimal config:
```
faibl_mailjet:
  accounts:
    account_1:
      api:
        key: mailjet_key_1
        secret: mailjet_secret_1
        delivery_enabled: true
```

### Full config:
```
faibl_mailjet:
  accounts:
    account_1:
      api:
        version: 3.1
        key: mailjet_key_1
        secret: mailjet_secret_1
    account_2:
      api:
        version: 3.1
        key: mailjet_key_2
        secret: mailjet_secret_2
  receiver_errors: 'error_address@mail.de'
  delivery_addresses: ['delivery_address1@mail.de', 'delivery_address2@mail.de']
  delivery_enabled: true        
```

### Config explained:
```
faibl_mailjet:
  accounts:
    ## Array of mailjet-accounts. 
    account_1:
      api:
        version: 3.1
        key: mailjet_key_1
        secret: mailjet_secret_1
    account_2:
      api:
        version: 3.1
        key: mailjet_key_2
        secret: mailjet_secret_2

  ## Configure default values for all accounts
  ## Can be overriden for each account
  
  ## Set error-receiver address 
  receiver_errors: 'error_address@mail.de'
  ## Disable delivery for all accounts. This activates mailjets sandbox 
  delivery_enabedl: false
  ## Override delivery-address for all mailings. Mailings will only be send to this address
  delivery_addresses: ['delivery_address1@mail.de', 'delivery_address2@mail.de']
```

### Test you configuration or template by sending test-mail
```
bin/console fbl:mailjet:test account_1 text --sender=sender@mail.de --receiver=yourname@mail.de
bin/console fbl:mailjet:test account_1 template --template-id=12345 --receiver=yourname@mail.de
```

## Inject service
### ServiceLocator
```
public function __construct(
  private readonly MailjetServiceLocator $mailjetServiceLocator
) {
}
```
Or inject your specific service directly by its service-id `fbl_mailjet.service.account_1`


## Send Mails
### Text Mails
```
$sender = new MailjetAddress('sender@mail.de', 'Optiopnal Name');
$receiver = new MailjetAddress('reveiver@mail.de', 'Optiopnal Name');

$mail = (new MailjetTextMail())
    ->setSender($sender)
    ->addReceiver($receiver)
    ->setSubject('Testmail send by faibl-mailjet-bundle')
    ->setTextPart('Nothing to say...');

$success = $this->mailjetServiceLocator->send('account_1', $mail);
```

### Template Mail
```
$receiver = new MailjetAddress('reveiver@mail.de', 'Optiopnal Name');

$mail = (new MailjetTemplateMail($templateId))
    ->addReceiver($receiver);

$success = $this->mailjetServiceLocator->send('account_2', $mail);
```

## Run Tests
```
php vendor/bin/simple-phpunit
```
