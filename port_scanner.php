<?php
/*
    Simple Port Scanner v1.0
    Code by Alan Barcellos
    github: https://github.com/barcellosDev
*/
$args = array('-h', '-p', '-t');

if (count($argv) > 1 and $argv[1] == $args[0])
{
  if (!isset($argv[1+1]))
  {
    echo "\033[31m Por favor defina o host para se conectar! \033[37m \n";
  } else
  {
    //echo $argv[2];
    if (isset($argv[2+1]) and $argv[2+1] == $args[1])
    {
      if (isset($argv[4]))
      {
        if (isset($argv[4+1]) and $argv[4+1] == $args[2])
        {
          if (isset($argv[6]))
          {
            $socket = @fsockopen($argv[2], $argv[4], $errno, $errstr, $argv[6]);
            if ($socket)
            {
              echo "\033[32m O servidor ".$argv[2]." está funcionando normalmente na porta ".$argv[4]."\033[37m \n";
            } else
            {
              echo "\033[31m O servidor não está no ar ou a porta não está aberta \033[37m \n";
            }

          } else
          {
            echo "\033[31m Escolha o tempo de espera da conexão! \033[37m \n";
          }
        } else
        {
          echo "\033[31m Define o argumento -t \033[37m \n";
        }

      } else
      {
        echo "\033[31m Defina a porta para aonde se conectar \033[37m \n";
      }
    } else
    {
      echo "\033[31m Defina o argumento -p \033[37m \n";
    }
  }
} else
{
  echo ("\33[1;33m
        *************************************************
        * -h Define o host para check                   *
        * -p Define a porta com que deseja se comunicar *
        * -t Define o timeout de conexão com o servidor *
        *************************************************
      ");
}
?>
