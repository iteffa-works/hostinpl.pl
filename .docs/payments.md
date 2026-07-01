# Payment gateways

Replace `domain.ru` with your panel domain.

## General callback URLs

| Purpose | URL |
|---------|-----|
| Success | `http://domain.ru/account/success` |
| Error | `http://domain.ru/account/error` |

## Gateway notification URLs

| Gateway | Notification URL |
|---------|------------------|
| FreeKassa | `http://domain.ru/result/freekassa` |
| Robokassa | `http://domain.ru/result/robokassa` |
| Enot.IO | `http://domain.ru/result/enot` |
| Anipay.IO | `http://domain.ru/result/anypay` |
| Unitpay | `http://domain.ru/result/unitpay` |
| QIWI P2P | `http://domain.ru/result/qiwi` |
| YooMoney | `http://domain.ru/result/yandexkassa` |

### YooMoney extra step

Set HTTP notification in YooMoney account:  
https://yoomoney.ru/transfer/myservices/http-notification

## Controllers

Payment result handlers live in `application/controllers/result/`:

- `freekassa.php`, `robokassa.php`, `unitpay.php`, `qiwi.php`, `yandexkassa.php`, `enot.php`, `anypay.php`, `litekassa.php`

## Admin default gateway

`oplatahostinpl` in config selects the primary top-up method in the user UI.
