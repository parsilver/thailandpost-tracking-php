## Thailand Post Tracking
PHP Library สำหรับ tracking พัสดุของไปรษณีย์ไทย

### สิ่งที่ต้องการ
```json
{
  "php": "^7.4|^8.0"
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
    'api_key' => 'xxxxxxxx'
]);

// ตัวเชื่อมต่อ api
$api = new Api($client);

// สร้างคำร้องขอ
$request = new Requests\GetItemsByBarcode(
    $barcodes = ['EY145587896TH', 'RC338848854TH']
);

// ได้ผลลัพท์กลับมา
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