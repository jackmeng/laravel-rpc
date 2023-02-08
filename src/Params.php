<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/2/6 0006 16:09
 */

namespace LaravelRpc;

use LaravelRpc\Exceptions\IllegalParameterTypeException;

class Params
{
    public function signature($data,$secret)
    {
        return md5($this->buildString($data).$secret);
    }

    protected function buildString(&$data,$parent_key='')
    {
        $isIndexArray = $this->isIndexArray($data);

        if (!$isIndexArray){
            ksort($data,SORT_STRING);
        }
        $data_str = '';
        foreach($data as $key=>$value){
            if ($isIndexArray){
                $strKey = empty($parent_key)?$key:$parent_key.'[]';
            }else{
                $strKey = empty($parent_key)?$key:$parent_key.'['.$key.']';
            }
            if (is_array($value)){
               $str = '&'.$this->buildString($value,$strKey);
            }elseif(is_string($value) || is_int($value) || is_float($value)){
                $str = '&'.$strKey.'='.$value;
            }elseif(is_bool($value)){
                $str = '&'.$strKey.'=';
                $str .= $value?'true':'false';
            }else{
                throw new IllegalParameterTypeException();
            }

            $data_str .= $str;
        }
        return ltrim($data_str,'&');
    }

    protected function isIndexArray(array &$data)
    {
        $values = array_values($data);
        $diff_key = array_diff_key($data,$values);
        return empty($diff_key);
    }

    public function check($data,$secret,$signature)
    {
        return $this->signature($data,$secret) === $signature;
    }
}