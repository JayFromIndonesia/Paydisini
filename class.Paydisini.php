<?php
/*
* Class PayDisini by RADITEK GROUP
* @version v1.0.0 (24 Juli 2024)
* @author PT. RAFFA DIGITAL TEKNOLOGI
* @website https://obfuscation.raditek.co.id
* @whatsapp (+62)819-1400-5555
* @copyright (c) 2017-2024 PT. RAFFA DIGITAL TEKNOLOGI, All Rights Reserved
*/


class Paydisini {
  const URL = 'https://api.paydisini.co.id/v1/';

  static $apiKey;

  static $cmd = [
    'chanel' => 'PaymentChannel',
    'guide' => 'PaymentGuide',
    'profile' => 'Profile',
    'order' => 'NewTransaction',
    'status' => 'StatusTransaction',
    'cancel' => 'CancelTransaction'
  ];

  public function __construct($apiKey) {
    Paydisini::$apiKey = $apiKey;
  }

  public function chanel() {
    return Paydisini::Request(self::URL, [
      'key' => Paydisini::$apiKey,
      'request' => 'payment_channel',
      'signature' => md5(
        Paydisini::$apiKey .
        self::$cmd['chanel']
      )
    ]);
  }

  public function guide($service) {
    return Paydisini::Request(self::URL, [
      'key' => Paydisini::$apiKey,
      'request' => 'payment_guide',
      'service' => $service,
      'signature' => md5(
        Paydisini::$apiKey .
        $service .
        self::$cmd['guide']
      )
    ]);
  }

  public function profile() {
    return Paydisini::Request(self::URL, [
      'key' => Paydisini::$apiKey,
      'request' => 'profile',
      'signature' => md5(
        Paydisini::$apiKey .
        self::$cmd['profile']
      )
    ]);
  }

  public function order($code, $service, $amount, $note, $wallet = null, $merchant = null, $email = null) {
    return Paydisini::Request(self::URL, [
      'key' => Paydisini::$apiKey,
      'request' => 'new',
      'merchant_id' => $merchant,
      'unique_code' => $code,
      'service' => $service,
      'amount' => $amount,
      'note' => $note,
      'valid_time' => 10800,
      'ewallet_phone' => $wallet,
      'customer_email' => $email,
      'type_fee' => 1,
      'signature' => md5(
        Paydisini::$apiKey .
        $code .
        $service .
        $amount .
        '10800' .
        self::$cmd['order']
      )
    ]);
  }

  public function status($code) {
    return Paydisini::Request(self::URL, [
      'key' => Paydisini::$apiKey,
      'request' => 'status',
      'unique_code' => $code,
      'signature' => md5(
        Paydisini::$apiKey .
        $code .
        self::$cmd['status']
      )
    ]);
  }

  public function cancel($code) {
    return Paydisini::Request(self::URL, [
      'key' => Paydisini::$apiKey,
      'request' => 'cancel',
      'unique_code' => $code,
      'signature' => md5(
        Paydisini::$apiKey .
        $code .
        self::$cmd['cancel']
      )
    ]);
  }

  public static function Request($url, $params = []) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    if (curl_errno($curl)) {
      throw new \Exception('[ERROR] Cannot get server response!');
    } else {
      $result = curl_exec($curl);
      curl_close($curl);
      $response = json_decode($result, true);
      return $response;
    }
  }
}

// Installasi
$paydisini = new Paydisini('Api_key_kamu');

// Melihat Payment Channel
// $Channel = $paydisini->chanel();
// echo json_encode($Channel, true);

// Instruksi Pembayaran
// $Guide = $paydisini->guide('code_method_pembayaran_paydisini');
// echo json_encode($Guide, true);

// Melihat Profile
// $Profile = $paydisini->profile();
// echo json_encode($Profile, true);

// Membuat Transaksi Baru
// $Order = $paydisini->order( 'code_transaction_kamu', 'code_method_pembayaran_paydisini', 'Nominal_transaksi_kamu', 'Catatan_Transaksi_kamu', 'Nomor_telepon');
// echo json_encode($Order, true);

// Memeriksa Status Transaksi
// $Status = $paydisini->status('code_transaction_kamu');
// echo json_encode($Status, true);

// Membatalkan Transaksi
// $Cancel = $paydisini->cancel('code_transaction_kamu');
// echo json_encode($Cancel, true);

