## Thailand Post Tracking

PHP Library สำหรับ tracking พัสดุของไปรษณีย์ไทย

อ้างอิงจากเว็บ APIs ของไปรษณีย์ไทย https://track.thailandpost.co.th/developerGuide

ซึ่ง Library ตัวนี้ทำหน้าที่ครอบ REST APIs ของทางไปรษณีย์ไทยอีกทีนึงเพื่อสะดวกในการใช้งาน

### สิ่งที่ต้องการ

```json
{
  "php": "^8.2",
  "ext-json": "*"
}
```

### ติดตั้งผ่าน Composer

```
composer require farzai/thailand-post
```

---

## เริ่มต้นใช้งาน

### ส่วนของ REST APIs

```php
use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

// ตั้งค่า
$client = ClientBuilder::create()
    // API Key ที่ได้มาจากการ generate ผ่านหน้าเว็บของไปรษณีย์ไทย
    ->setCredential('YOUR_API_KEY')
    ->build();

// เรียกใช้งานตัวเชื่อมต่อ api
$api = new ApiEndpoint($client);

try {
    // ส่งคำร้องขอเรื่อง ดึงสถานะของ barcode
    $response = $api->trackByBarcodes([
        // รายการที่ต้องการติดตาม
        'barcode' => ['EY145587896TH', 'RC338848854TH'],

        // Options
        'status' => 'all',
        'language' => 'TH',
    ]);
} catch (InvalidApiTokenException $e) {
    // กรณีที่ API Token ไม่ถูกต้อง
    exit($e->getMessage());
}

// คุณสามารถนำ json response มาใช้งานได้จากคำสั่งด้านล่างได้เลย
$array = $response->json();

// หรือ ต้องการเข้าไปยัง path ของ json
$countNumber = $response->json('response.track_count.count_number');

```

---

### ส่วนของ Webhook APIs

```php
use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\WebhookEndpoint;

$client = ClientBuilder::create()
    ->setCredential('YOUR_API_KEY')
    ->build();

$webhook = new WebhookEndpoint($client);

$response = $webhook->subscribeBarcodes([
    'barcode' => ['EY145587896TH', 'RC338848854TH'],
    'status' => 'all',
    'language' => 'TH',
    'req_previous_status' => true,
]);

// ตรวจสอบว่าทำงานถูกต้องหรือไม่
if ($response->isSuccessfull() && $response->json('status') === true) {
    $returnedJson = $response->json();

    // Or
    $message = $response->json('message');
    $items = $response->json('response.items');
    $trackCount = $response->json('response.track_count.count_number');
}

```

## การรับค่าจาก Webhook

เมื่อท่านตั้งค่า URL Webhook ของท่านแล้ว การนำข้อมูลที่ได้จากการส่งมาจาก Webhook มาใช้งาน

เราได้เตรียมตัวรับข้อมูลเอาไว้ตรวจสอบให้ท่านใช้งานสะดวกมากยิ่งขึ้นตามตัวอย่างด้านล่าง

```php
// ในหน้ารับข้อมูล

use Farzai\ThaiPost\Postman;

// คำสั่งนี้เอาไว้รับข้อมูลจาก Webhook
$entity = Postman::capture();

// ท่านสามารถตรวจสอบได้ว่าค่าที่ส่งมากจากไปรษณีย์ถูกต้องหรือไม่?
if ($entity->isValid()) {

    // ดึงค่าออกมา
    $entity->json('track_datetime');

    // ...
}
```

---

### การตั้งค่า

ทุกครั้งที่มีการเรียก API Tracking ต่างๆ Lib ตัวนี้จะคอยเรียก API Token เพื่อขอ Token จาก API ตัามตัวอย่างด้านล่าง
และทำการถือ Token ที่ได้มาแล้วทำไปเรียก API Tracking อีกที

```
GET: https://trackapi.thailandpost.co.th/post/api/v1/authenticate/token
```

ดังนั้น หากท่านต้องการที่จะทำ Cache Token เก็บไว้ก่อนเรียก API
ท่านสามารถเก็บ token ได้เองโดยการ implement ตาม Interface ด้านล่าง

```php
use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
```

ยกตัวอย่าง เช่น

```php
namespace App;

use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\ThaiPost\Exceptions\AccessTokenException;

class DatabaseAccessTokenStorage implements StorageRepositoryInterface
{
    /**
    * Get access token.
    *
    * @throws \Farzai\ThaiPost\Exceptions\AccessTokenException
    */
    public function getToken(): AccessTokenEntityInterface
    {
        // ทำการดึง Token จากที่เก็บไว้
        // ...

        // ถ้าไม่พบ Token ให้ทำการส่ง Exception ออกไป
        if (empty($token)) {
            throw new AccessTokenException('Token not found');
        }

        return new AccessTokenEntity(
            $token['access_token'],
            $token['expires_in'],
        );
    }

    /**
    * Save access token.
    *
    *
    * @throws \Farzai\ThaiPost\Exceptions\AccessTokenException
    */
    public function saveToken(AccessTokenEntityInterface $accessToken): void
    {
        // ทำการบันทึก Token ลงไป
    }

    /**
    * Clear access token.
    */
    public function forget(): void
    {
        // ลบ Token ทิ้ง
    }
}
```

การใช้งานนั้น เพียงแค่ท่านต้องการเรียกใช้งานเพียงแค่เพิ่ม StorageRepository ของท่านที่ท่านต้องการใช้งานเข้าไปใน `ClientBuilder` ดังตัวอย่างด้านล่าง

```php
use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use App\DatabaseAccessTokenStorage;


$client = ClientBuilder::create()
    ->setCredential('YOUR_API_KEY')
    ->setStorageRepository(new DatabaseAccessTokenStorage())
    ->build();

$api = new ApiEndpoint($client);

// ...
```
