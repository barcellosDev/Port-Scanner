<?php 
/**
 *  Port Scanner code by Alan Barcellos
 */
abstract class PortScanner
{
    private static $port, $hostname;

    private static $rotas = [
        'ip' => [
          '--host' => '-h'
        ],
        'port' => [
          '--port' => '-p'
        ],
        'time' => [
          '--time' => '-t'
        ]
    ];

    private static function help()
    {
      echo "-h ou --host para definir o host para scan\n";
      echo "-p ou --port para definir a porta de scan\n";
      echo "-t ou --time para definir o tempo de espera até se checar a porta\n\n";
      echo "
      ***     Portas mais utilizadas por servidores      ****
      *   --------------------------------------------------*
      *   21   | FTP                                        *
      *   22   | SSH                                        *
      *   23   | TELNET                                     *
      *   80   | Apache/WEB Service                         *
      *   8080 | Apache/WEB Service (probably behind proxy) *
      *   25   | SMTP/E-mail Service                        *
      *   3306 | RDP/Remote Desktop Protocol                *
      ";
    }

    public static function Scan()
    {
      global $argv;

      if (count($argv) == 1) 
      {
        self::help();
      } else 
      {
        $comandos = self::verificaArgs();

        if (is_array($comandos['PORTA'])) 
        {
          $erros = [];
          foreach ($comandos['PORTA'] as $k => $v)
          {
            $fsock = @fsockopen($comandos['IP'], $v, $errno, $errstr, $comandos['TEMPO']); // Código sucesso = 0, código failure = 10060
            $erros[$v] = $errno;
          }
          foreach ($erros as $i => $c)
          {
            if ($erros[$i] === 0)
            {
              echo "
              [+] A porta ".$i." está aberta!
              ";
            } else 
            {
              echo "
              [-] A porta ".$i." está fechada ou há um firewall bloqueando-a!
              ";
            }
          }
        } else 
        {
          $fsock = @fsockopen($comandos['IP'], $comandos['PORTA'], $errno, $errstr, $comandos['TEMPO']);
          if (!$fsock) 
          {
            echo "Porta ".$comandos['PORTA']." não está aberta no host ".$comandos['IP']." ou há um firewall bloqueando-a! \n";
          } else 
          {
            echo "Porta ".$comandos['PORTA']." aberta no host ".$comandos['IP']."\n";
          }
        }
      }
    }

    private static function verificaArgs()
    {
      global $argv;

      foreach ($argv as $key => $value) 
      {
        foreach (self::$rotas['ip'] as $key1 => $value1) 
        {
          if ($key1 == $value or $value1 == $value) 
          {
            $ip_formatado = self::validaIP($argv[$key+1]);
            if ($ip_formatado) 
            {
              $ip = $argv[$key+1];
            } elseif (is_string(self::$hostname))
            {
              $ip = self::$hostname;
            } else 
            {
              $ip = null;
            }
          }
        }
        foreach (self::$rotas['port'] as $key2 => $value2) 
        {
          if ($key2 == $value or $value2 == $value) 
          {
            $porta_formatado = self::validaPorta($argv[$key+1]);

            if (!is_array($porta_formatado)) 
            {
              if (is_bool($porta_formatado) and $porta_formatado == true) 
              {
                $porta = $argv[$key+1];
              } else
              {
                if (is_int($porta_formatado)) 
                {                  
                  $porta = $porta_formatado;
                } else 
                {
                  $porta = null;
                }
              }
            } else 
            {
              foreach ($porta_formatado as $k => $v) 
              {
                if (is_int($porta_formatado[$k]) and !empty($porta_formatado[$k])) 
                {
                  $porta = $porta_formatado;
                } else 
                {
                  $porta = null;
                }
              }
            }
          }
        }
        foreach (self::$rotas['time'] as $key3 => $value3) 
        {
          if ($key3 == $value or $value3 == $value) 
          {
            $time = (isset($argv[$key+1]) and is_numeric($argv[$key+1])) ? $argv[$key+1] : null;
          }
        }
      }
      $routes = ['ip' => $ip, 'porta' => $porta, 'tempo' => $time];
      foreach ($routes as $chave => $valor) 
      {
        if (is_array($routes['porta'])) 
        {
          $ports = $routes['porta'];
          foreach ($ports as $keyPort => $valuePort) 
          {
            if (is_null($ports[$keyPort]) or is_null($routes[$chave]))
            {
              echo 'O/A '.$chave.' informado(a) é inválido(a)!';
              exit();
            }
          }
        } else 
        {
          if (is_null($routes[$chave])) 
          {
            echo 'O/A '.$chave.' informado(a) é inválido(a)!';
            exit();
          }
        }
      }
      return [
        'IP' => $ip,
        'PORTA' => $porta,
        'TEMPO' => $time
      ];
    }

    private static function validaIP($ip)
    {
      if (isset($ip)) 
      {
        if (substr_count($ip, '.') <= 4)
        {
          $arr_ip = explode('.', $ip);
          $without = $arr_ip;
          unset($without[1]);
          $domains = [
            'comum' => [
              'www',
              'com'
            ],
            'alter' => [
              'br',
              'gov',
              'edu'
            ]
          ];
          foreach ($without as $key => $value)
          {
            if (in_array($without[$key], $domains['comum']) or in_array($without[$key], $domains['alter'])) 
            {
              $ip_format = implode('.', $arr_ip);
              $hostname = gethostbyname($ip_format);
              $arr_host = explode('.', (string)$hostname);
              foreach ($arr_host as $chave => $valor) 
              {
                if (is_numeric($arr_host[$chave])) 
                {
                  self::$hostname = $hostname;
                }
              }
            }
          }
        }

        if (substr_count($ip, '.') == 3) 
        {
          $ex = explode('.', (string)$ip);

          foreach ($ex as $key => $value) 
          {
            if ($ex[$key] === '' or !is_numeric($ex[$key])) 
            {
              return false;
            }
          }
          return true;
        } else 
        {
          return false;
        }
      } else
      {
        return false;
      }
    }

    private static function validaPorta($porta)
    {
      if (isset($porta)) 
      {
        $proibidos = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        if (strpos($porta, ',')) 
        {
          $portas = explode(',', $porta);
          foreach ($portas as $key => $value) 
          {
            if (is_numeric($portas[$key])) 
            {
              if (strlen($portas[$key]) <= 5 and !in_array($portas[$key], $proibidos)) 
              {
                self::$port[] = (int)$portas[$key];
              }
            } else 
            {
              self::$port[] = getservbyname($portas[$key], 'tcp');
            }
          }
          return self::$port;
        } else
        {
          if (!is_numeric($porta))
          {
            self::$port = getservbyname($porta, 'tcp');
            return self::$port;
          } else 
          {
            if (strlen($porta) <= 5 and !in_array($porta, $proibidos))
            {
              return true;
            } else 
            {
              return false;
            }
          }
        }
      }
    }
}
PortScanner::Scan();
?>