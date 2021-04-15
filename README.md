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
  delivery_disabled: true
  delivery_address: 'delivery_address@mail.de'
        
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
  ## Disable delivery for all accounts. This activates mailjets snadbox 
  delivery_disabled: true
  ## Override delivery-address for all mailings. Mailings will only be send to this address
  delivery_address: 'delivery_address@mail.de'
        
```

## Run Tests
```
php vendor/bin/simple-phpunit
```
