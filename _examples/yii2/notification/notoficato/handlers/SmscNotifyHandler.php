<?php
namespace common\handlers;


use libphonenumber\PhoneNumberUtil;
use Namshi\Notificator\Notification\Handler\HandlerInterface;
use Namshi\Notificator\Notification\Sms\SmsNotification;
use Namshi\Notificator\NotificationInterface;
use Yii;

class SmscNotifyHandler implements HandlerInterface
{
    /**
     * @var string логин клиента
     */
    private $login;

    /**
     * @var string пароль или MD5-хеш пароля в нижнем регистре
     */
    private $password;

    /**
     * @var bool использовать метод POST
     */
    private $post;

    /**
     * @var bool использовать HTTPS протокол
     */
    private $https;

    /**
     * @var string кодировка сообщения: utf-8, koi8-r или windows-1251 (по умолчанию)
     */
    private $charset;

    /**
     * @var string e-mail адрес отправителя
     */
    private $smtp_from;

    /**
     * @inheritDoc
     */
    public function __construct($login, $password, $post = true, $https = true, $charset = 'utf-8', $smtp_from = null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->post = $post;
        $this->https = $https;
        $this->charset = $charset;
        $this->smtp_from = $smtp_from;
    }

    /**
     * @inheritDoc
     */
    function shouldHandle(NotificationInterface $notification)
    {
        if ($notification instanceof SmsNotification) {
            $number = PhoneNumberUtil::getInstance()->parse($notification->getRecipientNumber(), 'KZ');
            return PhoneNumberUtil::getInstance()->isValidNumber($number);
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    function handle(NotificationInterface $notification)
    {
        /** @var SmsNotification $notification */
        $result = $this->send_sms($notification->getRecipientNumber(), $notification->getMessage(), 0, 0, 0, 0, false);

        return sizeof($result) === 4;
    }

    /**
     * Функция отправки SMS
     *
     * обязательные параметры:
     *
     * $phones - список телефонов через запятую или точку с запятой
     * $message - отправляемое сообщение
     *
     * необязательные параметры:
     *
     * $translit - переводить или нет в транслит (1,2 или 0)
     * $time - необходимое время доставки в виде строки (DDMMYYhhmm, h1-h2, 0ts, +m)
     * $id - идентификатор сообщения. Представляет собой 32-битное число в диапазоне от 1 до 2147483647.
     * $format - формат сообщения (0 - обычное sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms, 7 - mms, 8 - mail, 9 - call)
     * $sender - имя отправителя (Sender ID). Для отключения Sender ID по умолчанию необходимо в качестве имени
     * передать пустую строку или точку.
     * $query - строка дополнительных параметров, добавляемая в URL-запрос ("valid=01:00&maxsms=3&tz=2")
     * $files - массив путей к файлам для отправки mms или e-mail сообщений
     *
     * возвращает массив (<id>, <количество sms>, <стоимость>, <баланс>) в случае успешной отправки
     * либо массив (<id>, -<код ошибки>) в случае ошибки
     */
    protected function send_sms($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "", $files = array())
    {
        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1", "mail=1", "call=1");

        $m = $this->_send_cmd("send", "cost=3&phones=" . urlencode($phones) . "&mes=" . urlencode($message) .
            "&translit=$translit&id=$id" . ($format > 0 ? "&" . $formats[$format] : "") .
            ($sender === false ? "" : "&sender=" . urlencode($sender)) .
            ($time ? "&time=" . urlencode($time) : "") . ($query ? "&$query" : ""), $files);

        // (id, cnt, cost, balance) или (id, -error)

        if (YII_DEBUG) {
            if ($m[1] > 0)
                Yii::info("Сообщение отправлено успешно. ID: $m[0], всего SMS: $m[1], стоимость: $m[2], баланс: $m[3].");
            else
                Yii::info("Ошибка №" . -$m[1] . $m[0] ? ", ID: " . $m[0] : "");
        }

        return $m;
    }

    /**
     * SMTP версия функции отправки SMS
     */
    protected function send_sms_mail($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = "")
    {
        return mail("send@send.smsc.kz", "", $this->login . ":" . $this->password . ":$id:$time:$translit,$format,$sender:$phones:$message", "From: " . $this->smtp_from . "\nContent-Type: text/plain; charset=" . $this->charset . "\n");
    }

    /**
     * Функция получения стоимости SMS
     *
     * обязательные параметры:
     *
     * $phones - список телефонов через запятую или точку с запятой
     * $message - отправляемое сообщение
     *
     * необязательные параметры:
     *
     * $translit - переводить или нет в транслит (1,2 или 0)
     * $format - формат сообщения (0 - обычное sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms, 7 - mms, 8 - mail, 9 - call)
     * $sender - имя отправителя (Sender ID)
     * $query - строка дополнительных параметров, добавляемая в URL-запрос ("list=79999999999:Ваш пароль: 123\n78888888888:Ваш пароль: 456")
     *
     * возвращает массив (<стоимость>, <количество sms>) либо массив (0, -<код ошибки>) в случае ошибки
     */
    protected function get_sms_cost($phones, $message, $translit = 0, $format = 0, $sender = false, $query = "")
    {
        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1", "mms=1", "mail=1", "call=1");

        $m = $this->_send_cmd("send", "cost=1&phones=" . urlencode($phones) . "&mes=" . urlencode($message) .
            ($sender === false ? "" : "&sender=" . urlencode($sender)) .
            "&translit=$translit" . ($format > 0 ? "&" . $formats[$format] : "") . ($query ? "&$query" : ""));

        // (cost, cnt) или (0, -error)

        if (YII_DEBUG) {
            if ($m[1] > 0)
                Yii::info("Стоимость рассылки: $m[0]. Всего SMS: $m[1]");
            else
                Yii::info("Ошибка №" . -$m[1]);
        }

        return $m;
    }

    /**
     * Функция проверки статуса отправленного SMS или HLR-запроса
     *
     * $id - ID cообщения или список ID через запятую
     * $phone - номер телефона или список номеров через запятую
     * $all - вернуть все данные отправленного SMS, включая текст сообщения (0,1 или 2)
     *
     * возвращает массив (для множественного запроса двумерный массив):
     *
     * для одиночного SMS-сообщения:
     * (<статус>, <время изменения>, <код ошибки доставки>)
     *
     * для HLR-запроса:
     * (<статус>, <время изменения>, <код ошибки sms>, <код IMSI SIM-карты>, <номер сервис-центра>, <код страны регистрации>, <код оператора>,
     * <название страны регистрации>, <название оператора>, <название роуминговой страны>, <название роумингового оператора>)
     *
     * при $all = 1 дополнительно возвращаются элементы в конце массива:
     * (<время отправки>, <номер телефона>, <стоимость>, <sender id>, <название статуса>, <текст сообщения>)
     *
     * при $all = 2 дополнительно возвращаются элементы <страна>, <оператор> и <регион>
     *
     * при множественном запросе:
     * если $all = 0, то для каждого сообщения или HLR-запроса дополнительно возвращается <ID сообщения> и <номер телефона>
     *
     * если $all = 1 или $all = 2, то в ответ добавляется <ID сообщения>
     *
     * либо массив (0, -<код ошибки>) в случае ошибки
     */
    protected function get_status($id, $phone, $all = 0)
    {
        $m = $this->_send_cmd("status", "phone=" . urlencode($phone) . "&id=" . urlencode($id) . "&all=" . (int)$all);

        // (status, time, err, ...) или (0, -error)

        if (!strpos($id, ",")) {
            if (YII_DEBUG)
                if ($m[1] != "" && $m[1] >= 0)
                    Yii::info("Статус SMS = $m[0]" . $m[1] ? ", время изменения статуса - " . date("d.m.Y H:i:s", $m[1]) : "");
                else
                    Yii::info("Ошибка №" . -$m[1]);

            if ($all && count($m) > 9 && (!isset($m[$idx = $all == 1 ? 14 : 17]) || $m[$idx] != "HLR")) // ',' в сообщении
                $m = explode(",", implode(",", $m), $all == 1 ? 9 : 12);
        } else {
            if (count($m) == 1 && strpos($m[0], "-") == 2)
                return explode(",", $m[0]);

            foreach ($m as $k => $v)
                $m[$k] = explode(",", $v);
        }

        return $m;
    }

    /**
     * Функция получения баланса
     *
     * без параметров
     *
     * возвращает баланс в виде строки или false в случае ошибки
     */
    protected function get_balance()
    {
        $m = $this->_send_cmd("balance"); // (balance) или (0, -error)

        if (YII_DEBUG) {
            if (!isset($m[1]))
                Yii::info("Сумма на счете: " . $m[0]);
            else
                Yii::info("Ошибка №" . -$m[1]);
        }

        return isset($m[1]) ? false : $m[0];
    }


    /**
     * Функция вызова запроса. Формирует URL и делает 5 попыток чтения через разные подключения к сервису
     */
    protected function _send_cmd($cmd, $arg = "", $files = array())
    {
        $url = $_url = ($this->https ? "https" : "http") . "://smsc.kz/sys/$cmd.php?login=" . urlencode($this->login) . "&psw=" . urlencode($this->password) . "&fmt=1&charset=" . $this->charset . "&" . $arg;

        $i = 0;
        do {
            if ($i++)
                $url = str_replace('://smsc.kz/', '://www' . $i . '.smsc.kz/', $_url);

            $ret = $this->_read_url($url, $files, 3 + $i);
        } while ($ret == "" && $i < 5);

        if ($ret == "") {
            if (YII_DEBUG)
                Yii::info("Ошибка чтения адреса: $url");

            $ret = ","; // фиктивный ответ
        }

        $delim = ",";

        if ($cmd == "status") {
            parse_str($arg, $m);

            if (strpos($m["id"], ","))
                $delim = "\n";
        }

        return explode($delim, $ret);
    }

    /**
     * Функция чтения URL. Для работы должно быть доступно:
     * curl или fsockopen (только http) или включена опция allow_url_fopen для file_get_contents
     */
    protected function _read_url($url, $files, $tm = 5)
    {
        $ret = "";
        $post = $this->post || strlen($url) > 2000 || $files;

        if (function_exists("curl_init")) {
            static $c = 0; // keepalive

            if (!$c) {
                $c = curl_init();
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_CONNECTTIMEOUT, $tm);
                curl_setopt($c, CURLOPT_TIMEOUT, 60);
                curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            }

            curl_setopt($c, CURLOPT_POST, $post);

            if ($post) {
                list($url, $post) = explode("?", $url, 2);

                if ($files) {
                    parse_str($post, $m);

                    foreach ($m as $k => $v)
                        $m[$k] = isset($v[0]) && $v[0] == "@" ? sprintf("\0%s", $v) : $v;

                    $post = $m;
                    foreach ($files as $i => $path)
                        if (file_exists($path))
                            $post["file" . $i] = function_exists("curl_file_create") ? curl_file_create($path) : "@" . $path;
                }

                curl_setopt($c, CURLOPT_POSTFIELDS, $post);
            }

            curl_setopt($c, CURLOPT_URL, $url);

            $ret = curl_exec($c);
        } elseif ($files) {
            if (YII_DEBUG)
                Yii::info("Не установлен модуль curl для передачи файлов");
        } else {
            if (!$this->https && function_exists("fsockopen")) {
                $m = parse_url($url);

                if (!$fp = fsockopen($m["host"], 80, $errno, $errstr, $tm))
                    $fp = fsockopen("212.24.33.196", 80, $errno, $errstr, $tm);

                if ($fp) {
                    stream_set_timeout($fp, 60);

                    fwrite($fp, ($post ? "POST $m[path]" : "GET $m[path]?$m[query]") . " HTTP/1.1\r\nHost: smsc.kz\r\nUser-Agent: PHP" . ($post ? "\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($m['query']) : "") . "\r\nConnection: Close\r\n\r\n" . ($post ? $m['query'] : ""));

                    while (!feof($fp))
                        $ret .= fgets($fp, 1024);
                    list(, $ret) = explode("\r\n\r\n", $ret, 2);

                    fclose($fp);
                }
            } else
                $ret = file_get_contents($url);
        }

        return $ret;
    }
}
