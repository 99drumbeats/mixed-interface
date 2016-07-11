<?php

class Response
{
  /*
   * 按综合方式输出通信数据
   * @param integer $code 状态码
   * @param string $message 提示信息
   * @param array $data 数据
   * param string $type 数据类型
   * return string
   */
  const JSON = "json";
  public static function show($code, $message='', $data=array())
  {
    if (!is_numeric($code)){
      return '';
    }
    $type = isset($_GET['format']) ? $_GET['format'] : self::JSON;    //帮助客户端控制输出格式
    $result = array(
      'code' => $code,
      'message' => $message,
      'data' => $data,
    );
    if ($type == 'json'){
      self::json($code, $message, $data);
      exit;
    } elseif ($type == 'array'){
      var_dump($result);
    } elseif ($type == 'xml'){
      self::xmlEncode($code, $message, $data);
      exit;
    } else {
        //TODO 根据业务需求，其他格式
    }
    exit;
  }
  
  /*
  *按json方式输出通信数据
  */
  public static function json($code, $message='', $data=array()){
    if(!is_numeric($code)){
      return '';
    }
    $result = array(
      'code' => $code,
      'message' => $message,
      'data' => $data,
    );
    echo json_encode($result);
    exit;
  }
  
  /*
  *按xml方式输出通信数据
  */
  public static function xmlEncode($code, $message='', $data=array()){
    if (!is_numeric($code)){
      return '';
    }
    $result = array(
      'code' => $code,
      'message' => $message,
      'data' => $data,
    );
    header("Content-Type:text/xml");
    $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
    $xml .= "<root>\n";
    $xml .= self::xmlToEncde($result);
    $xml .= "</root>\n";
    echo $xml;
    exit;
  }

  /*
  *按xml方式输出通信数据
  *数据内容转化
  */
  public static function xmlToEncde($data){
    $xml = "";
    foreach ($data as $key => $value){
      $attr = "";
      if(is_numeric($key)){
        $attr = " id = '{$key}'";
        $key = "item";
      }
      $xml .= "<{$key}{$attr}>";
      $xml .= is_array($value) ? self::xmlToEncde($value):$value;
      $xml .="</{$key}>\n";
    }
    return $xml;
  }
}
