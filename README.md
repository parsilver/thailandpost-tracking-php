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

### ติดตั้ง
```
composer require farzai/thailand-post
```

### เริ่มต้นใช้งานเบื้องต้น

```php
use Farzai\ThaiPost\Endpoints\Api;
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Requests;

// ตั้งค่า
$client = new Client([
    // API Key ที่ได้มาจากการ generate ผ่านหน้าเว็บของไปรษณีย์ไทย
    'api_key' => 'xxxxxxxx'
]);

// ตัวเชื่อมต่อ api
$api = new Api($client);

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
$response = $api->getItemsByBarcode($request)

// ตรวจสอบว่าทำงานถูกต้องหรือไม่
if ($response->isOk()) {
    // คุณสามารถนำ json response มาใช้งานได้จากคำสั่งด้านล่างได้เลย
    $response->json(); // array
    
    // หรือ ต้องการเข้าไปยัง path ของ json 
    // สามารถใส่ parameter เข้าไปได้เลย
    $response->json('message'); // 
    
    // ในกรณีที่ลึกไปอีก 2 ชั้น
    $response->json('response.track_count.count_number');
}

```


### การตั้งค่า

เนื่องจากโดยปกติแล้ว library ตัวนี้จะใช้ session ในการเก็บ token ที่ได้จากการเรียก api
```
GET: https://trackapi.thailandpost.co.th/post/api/v1/authenticate/token
```


ท่านสามารถเปลี่ยนวิธีการเก็บ token ได้เองโดยการ implement `TokenStoreInterface`
```php
use Farzai\ThaiPost\Auth\TokenStoreInterface
```

ยกตัวอย่าง เช่น
```php
namespace App;

use Farzai\ThaiPost\Auth\TokenStoreInterface;

class CustomStore implements TokenStoreInterface
{
    /**
     * Save token
     * 
     * @param string $token
     */
    public function store(string $token)
    {
        // Save token here...
    }

    /**
     * Get token
     *
     * @return string
     */
    public function get()
    {
        // Retrieve token
        
        return '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }
}

```

เมื่อเรียกใช้งาน

```php
use Farzai\ThaiPost\Endpoints\Api;
use Farzai\ThaiPost\Client;
use App\CustomStore;

$client = new Client([
    'api_key' => 'xxxxxxxx'
]);

// เพิ่ม CustomStore ไปยัง Endpoint
$api = new Api($client, new CustomStore);

// Make request....
```