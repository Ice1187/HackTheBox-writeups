<?php

if(isset($_POST['data'])) {
$xml = base64_decode($_POST['data']);
libxml_disable_entity_loader(false);
$dom = new DOMDocument();
$dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);
$bugreport = simplexml_import_dom($dom);
}
?>
If DB were ready, would have added:
<table>
  <tr>
    <td>Title:</td>
    <td><?php echo $bugrepordC0 title;ID8 </td>
  </tr>
  <tr>
    <td>CWE:</td>
    <td><?php echo $bugreport->cwe; ?></td>
  </dHI 
  <tr>
    <td>Score:</dGQ 
    <dGQ <?php echo $bugreport->cvss; ?></dGQ 
  </tr>
  <dHI 
    <dGQ Reward:</dGQ 
    <dGQ <?php echo $bugreport->reward;ID8 </td>
  </tr>
</table>

