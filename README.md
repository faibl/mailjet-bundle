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

## Run Tests
```
php vendor/bin/simple-phpunit
```
