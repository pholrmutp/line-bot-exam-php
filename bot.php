<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// include composer autoload
require_once 'vendor/autoload.php';
 
// การตั้งเกี่ยวกับ bot
require_once 'bot_settings.php';
 
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
 
// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
 
// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');
 
// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);
if(!is_null($events)){
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = $events['events'][0]['message']['text'];
    $uid = $events['events'][0]['source']['userId'];
    $userMessage = strtolower($userMessage);
    switch ($typeMessage){
        case 'text':
            switch ($userMessage) {
                case "uid":
                    $textReplyMessage = "UID คุณ ".$uid;
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;
                case "i":
                    $picFullSize = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower';
                    $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower/240';
                    $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
                    break;
                case "v":
                    $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/240';
                    $videoUrl = "https://www.mywebsite.com/simplevideo.mp4";                
                    $replyData = new VideoMessageBuilder($videoUrl,$picThumbnail);
                    break;
                case "a":
                    $audioUrl = "https://www.mywebsite.com/simpleaudio.mp3";
                    $replyData = new AudioMessageBuilder($audioUrl,27000);
                    break;
                case "l":
                    $placeName = "ที่ตั้งโรงพยาบาลวิภาวดี";
                    $placeAddress = "51/3 ถ.งามวงศ์วาน เขตจตุจักร กรุงเทพฯ 10900";
                    $latitude = 13.8462543;
                    $longitude = 100.5599352;
                    $replyData = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);              
                    break;
                case "s":
                    $stickerID = 22;
                    $packageID = 2;
                    $replyData = new StickerMessageBuilder($packageID,$stickerID);
                    break;      
                case "im":
                    $imageMapUrl = 'https://www.vibhavadi.co.th/hi.jpg';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'This is Title',
                        new BaseSizeBuilder(699,1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'test image map',
                                new AreaBuilder(0,0,520,699)
                                ),
                            new ImagemapUriActionBuilder(
                                'http://www.ninenik.com',
                                new AreaBuilder(520,0,520,699)
                                )
                        )); 
                    break;          
                case "tm":
                    $replyData = new TemplateMessageBuilder('Confirm Template',
                        new ConfirmTemplateBuilder(
                                'Confirm template builder',
                                array(
                                    new MessageTemplateActionBuilder(
                                        'Yes',
                                        'menu'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'No',
                                        'Text NO'
                                    )
                                )
                        )
                    );
                    break;  
              case "reg":
                    $replyData = new TemplateMessageBuilder('Confirm Template',
                        new ConfirmTemplateBuilder(
                                'Confirm Register to Vibhavadi Hospital',
                                array(
                                    new MessageTemplateActionBuilder(
                                        'Yes',
                                        "http://www.vibhavadi.co.th/reg.php?a=$uid"
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'No',
                                        'NO'
                                    )
                                )
                        )
                    );
                    break;   
              case "menu":
                   // กำหนด action 4 ปุ่ม 4 ประเภท
                   $actionBuilder = array(
                   new MessageTemplateActionBuilder(
                   'Guild line',// ข้อความแสดงในปุ่ม
                   'tm' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                    ),
                    new UriTemplateActionBuilder(
                   'Vibhavadi Hospital', // ข้อความแสดงในปุ่ม
                   'https://www.vibhavadi.com'
                    ),
                    new UriTemplateActionBuilder(
                   'Register', // ข้อความแสดงในปุ่ม
                   'http://www.vibhavadi.co.th/reg.php?a=$uid'
                    ),      
                     new PostbackTemplateActionBuilder(
                     'ดู UID ', // ข้อความแสดงในปุ่ม
                      http_build_query(array(
                     'action'=>'buy',
                     'item'=>100
                      )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                     'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                      ),      
                          );
                     $imageUrl = 'https://www.vibhavadi.co.th/hi.jpg';
                     $replyData = new TemplateMessageBuilder('Button Template',
                     new ButtonTemplateBuilder(
                     'Vibhavadi Hospital', // กำหนดหัวเรื่อง
                     'Please select', // กำหนดรายละเอียด
                      $imageUrl, // กำหนด url รุปภาพ
                      $actionBuilder  // กำหนด action object
                      )
                         );              
                    break;  
                    case "menu2":
                       // กำหนด action 4 ปุ่ม 4 ประเภท
                       $actionBuilder = array(
                       new MessageTemplateActionBuilder(
                       'Button 1',// ข้อความแสดงในปุ่ม
                       'Button 1' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),
                        new UriTemplateActionBuilder(
                       'Button 2', // ข้อความแสดงในปุ่ม
                       'https://www.vibhavadi.com'
                       ),
                       new PostbackTemplateActionBuilder(
                       'Postback', // ข้อความแสดงในปุ่ม
                       http_build_query(array(
                       'action'=>'buy',
 
                        'item'=>100
                         )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                         'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),      
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                        new CarouselTemplateBuilder(
                        array(
                         new CarouselColumnTemplateBuilder(
                         'Title Carousel',
                         'Description Carousel',
                         'https://www.vibhavadi.co.th/hi2.jpg',
                          $actionBuilder
                          ),
                          new CarouselColumnTemplateBuilder(
                         'Title Carousel',
                         'Description Carousel',
                         'https://www.vibhavadi.co.th/hi3.jpg',
                         $actionBuilder
                          ),
                           new CarouselColumnTemplateBuilder(
                          'Title Carousel',
                           'Description Carousel',
                           'https://www.vibhavadi.co.th/hi4.jpg',
                            $actionBuilder
                            ),                                          
                            )
                            )
                            );
                           break;  
                    case "menu3":
                          $replyData = new TemplateMessageBuilder('Image Carousel',
                          new ImageCarouselTemplateBuilder(
                          array(
                          new ImageCarouselColumnTemplateBuilder(
                          'https://www.vibhavadi.co.th/hi.jpg',
                          new UriTemplateActionBuilder(
                          'Pholapat1', // ข้อความแสดงในปุ่ม
                         'https://www.vibhavadi.com'
                          )
                          ),
                          new ImageCarouselColumnTemplateBuilder(
                          'https://www.vibhavadi.co.th/hi2.jpg',
                          new UriTemplateActionBuilder(
                          'Pholapat2', // ข้อความแสดงในปุ่ม
                         'https://www.vibhavadi.com'
                    )
                )                                       
            )
        )
    );
    break;  
                default:
                    $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                    $replyData = new TextMessageBuilder($textReplyMessage);         
                    break;                                      
            }
            break;
        default:
            $textReplyMessage = json_encode($events);
            $replyData = new TextMessageBuilder($textReplyMessage);         
            break;  
    }
}
//l ส่วนของคำสั่งตอบกลับข้อความ
$response = $bot->replyMessage($replyToken,$replyData);
 
// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();


?>
