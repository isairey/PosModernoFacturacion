<?php

class ModelCron extends Model
{
  public $msg = array();
  public $err = array();

  public function Run($action)
  {
    $this->CheckForUpdate('CHECKFORUPDATE');
    $this->DBBackup('DBBACKUP');
    $this->SendCustomerBirthDaySMS('SENDCUSTOMERBIRTHDAYSMS');
    $this->PushSqlToRemoteServer('PUSHSQLTOREMOTESERVER');
  }

  public function SendCustomerBirthDaySMS($action, $store_id = null)
  {
    $store_id = $store_id ? $store_id : store_id();
    $total = 0;
    $sms_model = $this->registry->get('loader')->model('sms');
    $gw = $sms_model->getSMSGateway();
    $customers = get_today_birthday_customers();
    if (!empty($customers)) {
      foreach ($customers as $customer) {
        if ($customer['customer_mobile']) {
          $gw->send($customer['customer_mobile'], trans('birthday_sms_text'));
          $total++;
        }
      }
    } else {
      $this->err[] = $action . ': Customer list is empty';
    }
    $this->msg[] = $action . ': Total: ' . $total . ' SMS sent';
    return true;
  }

  public function DBBackup($action) 
  {
    $statement = $this->db->prepare("SHOW TABLES");
    $statement->execute();
    $tables = $statement->fetchAll(PDO::FETCH_NUM);
    $backup = $this->MakeBackup($tables);
    $filename = 'mpos-backup-on-' . date("Y-m-d") . '.txt';
    $dirname = DIR_BACKUP . $filename;
    write_file($dirname, $backup);
    $files = glob(DIR_BACKUP.'*.txt', GLOB_BRACE);
    $now   = time();
    foreach ($files as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * 30) {
                unlink($file);
            }
        }
    }
    $this->msg[] = $action . ': Backup file successfully saved to > '.DIR_BACKUP;
    return true;
  }

  public function MakeBackup($tables = array())
  {
    $exclude_tables = array('users','user_group','user_to_store');
    $output = '';
    if (empty($tables)) {
      $statement = $this->db->prepare("SHOW TABLES");
      $statement->execute();
      $tables = $statement->fetchAll(PDO::FETCH_NUM);
    }

    foreach ($tables as $table) 
    {
      $table = is_array($table) && isset($table[0]) ? $table[0] : $table;
      if (in_array($table, $exclude_tables)) continue;
      $output .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";
      $statement = $this->db->prepare("SELECT * FROM `" . $table . "`");
      $statement->execute();
      $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
      foreach ($rows as $result) 
      {
        $fields = '';
        foreach (array_keys($result) as $value) {
          $fields .= '`' . $value . '`, ';
        }
        $values = '';
        foreach (array_values($result) as $value) {
          $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
          $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
          $value = str_replace('\\', '\\\\',  $value);
          $value = str_replace('\'', '\\\'',  $value);
          $value = str_replace('\\\n', '\n',  $value);
          $value = str_replace('\\\r', '\r',  $value);
          $value = str_replace('\\\t', '\t',  $value);
          $values .= '\'' . $value . '\', ';
        }
        $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
      }
      $output .= "\n\n";
    }
    return $output;
  }

  public function TableBackup() 
  {
    $dirname = date("Y-m-d").DIRECTORY_SEPARATOR;
    if (!is_dir(DIR_BACKUP.$dirname)) {
      @mkdir(DIR_BACKUP.$dirname);
    }
    $statement = $this->db->prepare("SHOW TABLES");
    $statement->execute();
    $tables = $statement->fetchAll(PDO::FETCH_NUM);
    foreach ($tables as $table) {
      $table = $table[0];
      $backup = $this->MakeTableBackup($table);
      $filename = $table.'.txt';
      $dirname = DIR_BACKUP.$dirname.$filename;
      write_file($dirname, $backup);
      $files = glob(DIR_BACKUP.'*.txt', GLOB_BRACE);
      $now   = time();
      foreach ($files as $file) {
          if (is_file($file)) {
              if ($now - filemtime($file) >= 60 * 60 * 24 * 30) {
                  @unlink($file);
              }
          }
      }
    }
    $this->msg[] = 'Backup files successfully saved';
    return true;
  }

  public function MakeTableBackup($table)
  {
    $output = '';
    $table = is_array($table) && isset($table[0]) ? $table[0] : $table;
    $output .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";
    $statement = $this->db->prepare("SELECT * FROM `" . $table . "`");
    $statement->execute();
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $result) {
      $fields = '';
      foreach (array_keys($result) as $value) {
        $fields .= '`' . $value . '`, ';
      }

      $values = '';
      foreach (array_values($result) as $value) {
        $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
        $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
        $value = str_replace('\\', '\\\\',  $value);
        $value = str_replace('\'', '\\\'',  $value);
        $value = str_replace('\\\n', '\n',  $value);
        $value = str_replace('\\\r', '\r',  $value);
        $value = str_replace('\\\t', '\t',  $value);

        $values .= '\'' . $value . '\', ';
      }
      $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
    }
    $output .= "\n\n";
    return $output;
  }

  public function _init($action='INIT')
  {
    if (!checkInternetConnection()) 
    {
      $this->err[] = $action . ': Internet connection problem';
      return false;
    }
    $curl = curl_init(ROOT_URL.'/index.php?esnecilchk=QWFMNB234567JHGF09876534WER12345678lkjhgfds');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $info = array(
      'for' => 'important',
      'username' => get_pusername(),
      'purchase_code' => get_pcode(),
      'ip_address' => get_real_ip(),
      'mac_address' => json_encode(getMAC()),
    );
    $response = apiCall($info);
  }

  private function IsUpdateAvailable()
  {
    if (settings('is_update_available')) {return true;};
    $data = array(
      'action' => 'update', 
      'purchase_code' => get_pcode(), 
      'username' => get_pusername(),
      'version' => settings('version'),
    );   
    $response = apiCall($data);
    if(!is_object($response) || $response->status == 'error') {
      return false;
    }
    $update_info = json_decode($response->update_info,true);
    if (isset($update_info['version']) && $update_info['version'] != settings('version')) {
      $statement = $this->db->prepare("UPDATE `settings` SET `is_update_available` = ?, `update_version` = ?, `update_link` = ? WHERE `id` = ?");
      $statement->execute(array(1, $update_info['version'], $update_info['link'], 1));
      return true;;
    } else {
      $statement = $this->db->prepare('UPDATE `settings` SET `is_update_available` = ?, `update_version` = ?, `update_link` = ? WHERE `id` = ?');
      $statement->execute(array(0, NULL, NULL, 1));
        return false;
    }
    return false;
  }

  public function CheckForUpdate($action)
  {
    if ($this->IsUpdateAvailable()) {
      $this->msg[] = $action . ': Update available';
    } else {
      $this->msg[] = $action . ': System is up to date :)';
    }
  }

  public function PushSqlToRemoteServer($action)
  {
    if (!checkInternetConnection()) 
    {
      $this->err[] = $action . ': Internet connection problem';
      return false;
    }

    if (!SYNCSERVERURL || !SYNCHRONIZATION) 
    {
      $this->err[] = $action . ': Synchronization was disabled from config.php file';
      return false;
    }

    $filePath = DIR_LOG.'sql.txt';
    $info = array(
      'username' => 'itsolution24',
      'password' => '1993',
      'action' => 'sync',
      'data' =>  json_encode($this->ReadSyncData($filePath)),
    );
    $apiCall = apiCall($info,SYNCSERVERURL);
    if(!$apiCall || (property_exists($apiCall, 'status') && $apiCall->status == 'error')) 
    {
      $this->err[] = $action . ': Remote server problem';
      return false;
    }
    if (file_exists($filePath) && is_file($filePath)) {
      $handle = fopen($filePath, "r+");
      flock($handle,LOCK_EX);
      ftruncate($handle, 0);
      flock($handle,LOCK_UN);
      fclose($handle);
    }
    $this->msg[] = $action . ': Synchronization successfully done!';
    return true;
  }

  public function ReadSyncData($path) 
  {
    $data = array();
    if (file_exists($path) && is_file($path)) {
      $handle = fopen($path, "r");
      flock($handle,LOCK_EX);
      while(!feof($handle)) {
        $lines = array();
        $count = 0;
        $inc = 0;
        while (!feof($handle)) {
          $line = explode('|', trim(fgets($handle)));
          if (isset($line[1])) {
            $part1 = $line[0];
            $part2 = unserialize($line[1]);
            if (is_array($part2)) {
              $data[$inc]['sql'] = $part1;
              $data[$inc]['args'] = $part2;
            }
          }
          $inc++;
        }
      }
      flock($handle,LOCK_UN);
      fclose($handle);
    }
    return $data;
  }
}