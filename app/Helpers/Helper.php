<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Config,
  Auth,
  DB;

class Helper
{
  use ApiResponse;

  /**
   * Summary of set_society_wise_database
   * @param mixed $database_name
   * @return bool
   */
  public function set_society_wise_database($database_name)
  {
    try {
      // create database
      $this->create_database($database_name);
      // configure database connection
      $this->configure_database_connection($database_name);
      // create database
      $this->create_database($database_name);
      // run migration
      $this->run_migrations();
      // run seeders
      $this->run_seeders();
      if (Auth::user() && Auth::user()->usertype == 1) {
        $this->add_admin_user($database_name);
        return true;
      }
    } catch (\Exception $ex) {
      dd($ex->getMessage());
    }
  }

  /**
   * Summary of create_database
   * @param mixed $database_name
   * @return void
   */
  public function create_database($database_name)
  {
    try {
      DB::statement("CREATE DATABASE IF NOT EXISTS `$database_name`");
    } catch (\Exception $ex) {
      dd($ex->getMessage());
    }
  }

  /**
   * Summary of configure_database_connection
   * @param mixed $database_name
   * @return void
   */
  public function configure_database_connection($database_name)
  {
    // Set the database connection dynamically
    Config::set(['database.connections.society.database' => $database_name]);
    Config::set("database.default", "society");

    // Optionally, you can set the default connection to 'society' for the duration of this request

    DB::reconnect();

    // Optionally, you can set the default connection to 'society' for the duration of this request
    DB::setDefaultConnection('society');
  }

  /**
   * Summary of run_migrations
   * @return void
   */
  public function run_migrations()
  {
    //        Config::set(['database.connections.company.database' => $dbName]);
    //        dd(  Config::get("database.connections.company.database"));
    Artisan::call('migrate', [
      //            '--database' => $dbName,
      '--path' => 'database/migrations/society'
    ]);
  }

  /**
   * Summary of run_seeders
   * @return void
   */
  public function run_seeders()
  {
    //        config(['database.connections.company.database' => $dbName]);
    Artisan::call('db:seed', [
      '--class' => 'DatabaseSeeder',
      '--force' => true,
    ]);
    //        dd(  Config::get("database.connections.company.database"));
  }

  /**
   * Summary of generateRandomPassword
   * @param mixed $length
   * @return string
   */
  public function generateRandomPassword($length = 8)
  {
    $password = "";
    // Generate a random string
    // Ensure the password contains at least one uppercase letter, one lowercase letter, one digit, and one special character
    $password .= Str::random(3, 'abcdefghijklmnopqrstuvwxyz');
    $password .= Str::random(2, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    $password .= Str::random(2, '0123456789');
    $specialChars = '!@#$%^&*';
    $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];
    // Shuffle the password to mix characters
    $password = str_shuffle($password);
    return substr($password, 0, $length);
  }

  /**
   * Summary of send_sms
   * @param mixed $mobile
   * @param mixed $message
   * @return bool|\Illuminate\Http\JsonResponse
   */
  public function send_sms($mobile, $message)
  {
    try {
      $user_name = env('SMS_USER_NAME');
      $password = env('SMS_PASSWORD');
      $sender_id = env('SMS_SENEDR_ID');
      $post_data = array(
        'mobile' => $user_name,
        'pass' => $password,
        'senderid' => $sender_id,
        'to' => "91" . $mobile,
        'msg' => $message
      );
      $url = env('SMS_API_URL'); /* API URL */
      $ch = curl_init(); /* init the resource */
      curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post_data
      ));
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); /* Ignore SSL certificate verification */
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      $output = curl_exec($ch); /* get response */
      // dd($output);
      curl_close($ch);
      if ($output) {
        return true;
      } else {
        echo "Send Otp Error : " . $output;
      }
    } catch (\Exception $ex) {
      // dd($ex->getMessage());
      return $this->customResponse(-1, null, $ex->getMessage());
    }
  }
}
