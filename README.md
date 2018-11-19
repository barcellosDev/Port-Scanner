# Scanner de portas

**Scanner de portas utilizando sockets com o protocolo TCP**
<br>
<br>
_**Uso**:_
<br>
<table>
<tr>
  <th>Comando</th>
  <th>Opções</th>
  <th>Definição</th>
</tr>
<tr>
  <td rowspan="3">php port_scanner.php</td>
  <td>-h ou --host</td>
  <td>Define o host para se conectar</td>
</tr>
<tr>
  <td>-p ou --port</td>
  <td>Define a porta para se conectar. A porta pode ser tanto no formato numérico (80,443,25) quanto no formato de serviço (http,smtp,ftp), mas este último não funciona perfeitamente para todas as portas disponíveis.</td>
</tr>
<tr>
  <td>-t ou --time</td>
  <td>Define o tempo total de resposta do host</td>
</tr>
</table>

<br>

**Exemplo:**

<ul>
  <li>php port_scanner.php -h www.google.com -p 80 -t 1</li>
  <li>php port_scanner.php -h www.google.com -p 80,443 -t 5</li>
  <li>php port_scanner.php -h www.google.com -p http,443,smtp -t 3</li>
</ul>