## Thailand Post Tracking
PHP Library สำหรับ tracking พัสดุของไปรษณีย์ไทย

อ้างอิงจากเว็บ APIs ของไปรษณีย์ไทย https://track.thailandpost.co.th/developerGuide

ซึ่ง Library ตัวนี้ทำหน้าที่ครอบ REST APIs ของทางไปรษณีย์ไทยอีกทีนึงเพื่อสะดวกในการใช้งาน

### สิ่งที่ต้องการ
```json
{
  "php": "^7.4|^8.0",
  "ext-json" : "*"
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
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\RestApi\Endpoint;
use Farzai\ThaiPost\RestApi\Requests;

// ตั้งค่า
$client = new Client([
    // API Key ที่ได้มาจากการ generate ผ่านหน้าเว็บของไปรษณีย์ไทย
    'api_key' => 'xxxxxxxx'
]);

// ตัวเชื่อมต่อ api
$api = new Endpoint($client);

// สร้างคำร้องขอเรื่อง ดึงสถานะของ barcode 
$request = new Requests\GetItemsByBarcode(
    $barcodes = ['EY145587896TH', 'RC338848854TH']
);

// (optional) หากต้องการตั้งค่าภาษา
$request->setLanguage("TH");

// (optional) ตั้งค่ากรองสถานะ
$request->setStatus("all");

// ท่านสามารถดูรายละเอียด parameter ต่างๆได้จากที่นี่
// https://track.thailandpost.co.th/developerGuide

// เมื่อเรียกคำสั่งด้านล่าง จะเสมือนเรียก api ตามตัวอย่างนี้
// POST: https://trackapi.thailandpost.co.th/post/api/v1/track
$response = $api->getItemsByBarcode($request);

// ตรวจสอบว่าทำงานถูกต้องหรือไม่
if ($response->isOk()) {

    // คุณสามารถนำ json response มาใช้งานได้จากคำสั่งด้านล่างได้เลย
    // @return array
    $response->json();
    
    // หรือ ต้องการเข้าไปยัง path ของ json 
    // สามารถใส่ parameter เข้าไปได้เลย
    $response->json('message');
    
    // ในกรณีที่ลึกไปอีก 2 ชั้น
    $response->json('response.track_count.count_number');
}

```

---

### ส่วนของ Webhook APIs

```php
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Webhook\Endpoint;
use Farzai\ThaiPost\Webhook\Requests;

// ตั้งค่า
$client = new Client([
    // API Key ที่ได้มาจากการ generate ผ่านหน้าเว็บของไปรษณีย์ไทย
    'api_key' => 'xxxxxxxx'
]);

// ตัวเชื่อมต่อ api
$api = new Endpoint($client);

// สร้างคำร้องขอเรื่อง ดึงสถานะของ barcode 
$request = new Requests\SubscribeByBarcode(
    $barcodes = ['EY145587896TH', 'RC338848854TH']
);

// (optional) หากต้องการตั้งค่าภาษา
$request->setLanguage("TH");

// (optional) ตั้งค่ากรองสถานะ
$request->setStatus("all");

// (optional) ต้องการข้อมูลการติดตามสถานะสิ่งของ
$request->withPreviousStatus();

// ท่านสามารถดูรายละเอียด parameter ต่างๆได้จากที่นี่
// https://track.thailandpost.co.th/developerGuide

// เมื่อเรียกคำสั่งด้านล่าง จะเสมือนเรียก api ตามตัวอย่างนี้
// POST: https://trackwebhook.thailandpost.co.th/post/api/v1/hook
$response = $api->subscribeByBarcode($request);

// ตรวจสอบว่าทำงานถูกต้องหรือไม่
if ($response->isOk()) {

    // คุณสามารถนำ json response มาใช้งานได้จากคำสั่งด้านล่างได้เลย
    // @return array
    $response->json();
}

```

## การรับค่าจาก Webhook
เมื่อท่านตั้งค่า URL Webhook ของท่านแล้ว การนำข้อมูลที่ได้จากการส่งมาจาก Webhook มาใช้งาน

เราได้เตรียมตัวรับข้อมูลเอาไว้ตรวจสอบให้ท่านใช้งานสะดวกมากยิ่งขึ้นตามตัวอย่างด้านล่าง

```php
// ในหน้ารับข้อมูล

use Farzai\ThaiPost\Postman;

// คำสั่งนี้เอาไว้รับข้อมูลจาก Webhook
/** @var \Farzai\ThaiPost\Webhook\Entity\HookDataEntity $data */
$data = Postman::capture();

// ท่านสามารถตรวจสอบได้ว่าค่าที่ส่งมากจากไปรษณีย์ถูกต้องหรือไม่?
if ($data->isValid()) {
    // ดึงค่าออกมา
    $data->track_datetime
    
    /** @var \Farzai\ThaiPost\Webhook\Entity\ItemEntity $item */
    foreach ($data->items as $item) {
        // ดึงค่าจาก Items
        $item->barcode;
        $item->delivery_datetime;
        $item->delivery_status;
        
        // Field อื่นๆสามารถอ้างอิงได้จากเอกสารของทางไปรษณีย์ไทย....
    }
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
ท่านสามารถเก็บ token ได้เองโดยการ implement `TokenStore`
```php
use Farzai\ThaiPost\Contracts\TokenStore
```

ยกตัวอย่าง เช่น

```php
namespace App;

use Farzai\ThaiPost\Contracts\TokenStore;
use Farzai\ThaiPost\Entity\TokenEntity;

class FilesystemStore implements TokenStore
{

    private $filename = "thailand-post--token.txt";

    /**
     * Save token
     * 
     * @param TokenEntity $token
     * @return mixed
     */
    public function save(TokenEntity $token)
    {
        // เก็บลง Database หรือ เก็บไว้ในไฟล์ก็ได้
        // เช่น
        file_put_contents($this->resolveFilePath(), $token->asJson());
    }

    /**
     * Get Token
     * 
     * @return TokenEntity|null
     */
    public function get()
    {
        $file = file_get_contents($this->resolveFilePath());
        
        $json = @json_decode($file, true);
        
        if ($json) {
            return TokenEntity::fromArray($json);
        }
    }

    /**
     * Check token has stored
     *
     * @return bool
     */
    public function has()
    {
        // ตรวจสอบว่าไม่ Token อยู่หรือไม่
        return file_exists($this->resolveFilePath());
    }
    
    
    private function resolveFilePath()
    {
        return DIRECTORY_SEPARATOR . 
                trim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . 
                DIRECTORY_SEPARATOR . 
                ltrim($this->filename, DIRECTORY_SEPARATOR);
    }
}

```

เมื่อเรียกใช้งาน

```php
use Farzai\ThaiPost\RestApi\Endpoint;
use Farzai\ThaiPost\Client;
use App\FilesystemStore;

$client = new Client([
    'api_key' => 'xxxxxxxx'
]);

// เพิ่ม FilesystemStore ไปยัง Endpoint
$api = new Endpoint($client);

$api->setTokenStore(new FilesystemStore)

// Make request....
```
