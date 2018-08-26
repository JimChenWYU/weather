# Weather

基于 [高德开放平台](https://lbs.amap.com/) 的 PHP 天气信息组件。

## 安装

```shell
$ composer require jimchen/weather -vvv
```

## 配置

在使用本扩展之前，你需要去 [高德开放平台](https://lbs.amap.com/) 注册账号，然后创建应用，获取应用的 API Key。

## 使用

```php
use JimChen/Weather/Weather;

$key = 'xxxxxxxxxxxxxxxxxxxxxxxx';

$weather = new Weather($key);
```

### 获取实时天气

```php
$response = $weather->getWeather('广州');
```

示例:
```json
{
  "status": "1",
  "count": "1",
  "info": "OK",
  "infocode": "10000",
  "lives": [
    {
      "province": "广东",
      "city": "广州市",
      "adcode": "440100",
      "weather": "阴",
      "temperature": "26",
      "winddirection": "北",
      "windpower": "7",
      "humidity": "84",
      "reporttime": "2018-08-26 19:00:00"
    }
  ]
}
```

### 获取近期天气预报

```php
$response = $weather->getWeather('广州', 'all');
```

示例:
```json
{
  "status": "1",
  "count": "1",
  "info": "OK",
  "infocode": "10000",
  "forecasts": [
    {
      "city": "广州市",
      "adcode": "440100",
      "province": "广东",
      "reporttime": "2018-08-26 18:00:00",
      "casts": [
        {
          "date": "2018-08-26",
          "week": "7",
          "dayweather": "雷阵雨",
          "nightweather": "雷阵雨",
          "daytemp": "34",
          "nighttemp": "27",
          "daywind": "无风向",
          "nightwind": "无风向",
          "daypower": "≤3",
          "nightpower": "≤3"
        },
        {
          "date": "2018-08-27",
          "week": "1",
          "dayweather": "雷阵雨",
          "nightweather": "雷阵雨",
          "daytemp": "32",
          "nighttemp": "25",
          "daywind": "无风向",
          "nightwind": "无风向",
          "daypower": "≤3",
          "nightpower": "≤3"
        },
        {
          "date": "2018-08-28",
          "week": "2",
          "dayweather": "大雨",
          "nightweather": "大雨",
          "daytemp": "30",
          "nighttemp": "25",
          "daywind": "无风向",
          "nightwind": "无风向",
          "daypower": "≤3",
          "nightpower": "≤3"
        },
        {
          "date": "2018-08-29",
          "week": "3",
          "dayweather": "大雨",
          "nightweather": "中雨-大雨",
          "daytemp": "30",
          "nighttemp": "25",
          "daywind": "南",
          "nightwind": "无风向",
          "daypower": "4",
          "nightpower": "≤3"
        }
      ]
    }
  ]
}
```

### 获取 XML 格式返回值

以上两个方法第二个参数为返回值类型，可选`json`与`xml`，默认`json`:
```php
$response = $weather->getWeather('广州', 'all', 'xml');
```

示例: 
```xml
<response>
  <status>1</status>
  <count>1</count>
  <info>OK</info>
  <infocode>10000</infocode>
  <forecasts type="list">
    <forecast>
      <city>广州市</city>
      <adcode>440100</adcode>
      <province>广东</province>
      <reporttime>2018-08-26 18:00:00</reporttime>
      <casts type="list">
        <cast>
          <date>2018-08-26</date>
          <week>7</week>
          <dayweather>雷阵雨</dayweather>
          <nightweather>雷阵雨</nightweather>
          <daytemp>34</daytemp>
          <nighttemp>27</nighttemp>
          <daywind>无风向</daywind>
          <nightwind>无风向</nightwind>
          <daypower>≤3</daypower>
          <nightpower>≤3</nightpower>
        </cast>
        <cast>
          <date>2018-08-27</date>
          <week>1</week>
          <dayweather>雷阵雨</dayweather>
          <nightweather>雷阵雨</nightweather>
          <daytemp>32</daytemp>
          <nighttemp>25</nighttemp>
          <daywind>无风向</daywind>
          <nightwind>无风向</nightwind>
          <daypower>≤3</daypower>
          <nightpower>≤3</nightpower>
        </cast>
        <cast>
          <date>2018-08-28</date>
          <week>2</week>
          <dayweather>大雨</dayweather>
          <nightweather>大雨</nightweather>
          <daytemp>30</daytemp>
          <nighttemp>25</nighttemp>
          <daywind>无风向</daywind>
          <nightwind>无风向</nightwind>
          <daypower>≤3</daypower>
          <nightpower>≤3</nightpower>
        </cast>
        <cast>
          <date>2018-08-29</date>
          <week>3</week>
          <dayweather>大雨</dayweather>
          <nightweather>中雨-大雨</nightweather>
          <daytemp>30</daytemp>
          <nighttemp>25</nighttemp>
          <daywind>南</daywind>
          <nightwind>无风向</nightwind>
          <daypower>4</daypower>
          <nightpower>≤3</nightpower>
        </cast>
      </casts>
    </forecast>
  </forecasts>
</response>
```

### 参数说明

```
array | string   getLiveWeather(string $city, string $format = 'json')
array | string   getForcastsWeather(string $city, string $format = 'json')
```

> - `$city` - 城市名/[高德地址位置 adcode](https://lbs.amap.com/api/webservice/guide/api/district)，比如：“深圳” 或者（adcode：440300）；
> - `$format` - 输出的数据格式，默认为 json 格式，当 output 设置为 “`xml`” 时，输出的为 XML 格式的数据。

### 在 Laravel 中使用

在 Laravel 中使用也是同样的安装方式，配置写在 `config/services.php` 中：

```php
    .
    .
    .
     'weather' => [
        'key' => env('WEATHER_API_KEY'),
    ],
```

然后在 `.env` 中配置 `WEATHER_API_KEY` ：

```env
WEATHER_API_KEY=xxxxxxxxxxxxxxxxxxxxx
```

可以用两种方式来获取 `JimChen\Weather\Weather` 实例：

#### 方法参数注入

```php
    .
    .
    .
    public function edit(Weather $weather) 
    {
        $response = $weather->getLiveWeather('深圳');
    }
    .
    .
    .
```

#### 服务名访问

```php
    .
    .
    .
    public function edit() 
    {
        $response = app('weather')->getLiveWeather('深圳');
    }
    .
    .
    .

```

## 参考

- [高德开放平台天气接口](https://lbs.amap.com/api/webservice/guide/api/weatherinfo/)

## License

MIT