<?php
/**
 *
 * @author jackmeng <jiekemeng@gmail.com>
 * @date 2023/3/17 0017 16:27
 */

namespace LaravelRpc\Enum;

class VerifyCipher
{
    const AES128CBC = 'aes-128-cbc';
    const AES256CBC = 'aes-256-cbc';
    const AES128GCM = 'aes-128-gcm';
    const AES256GCM = 'aes-256-gcm';
}