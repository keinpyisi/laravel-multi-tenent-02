<?php

return [
    'accepted' => ':attribute を承認してください。',
    'active_url' => ':attribute は有効なURLではありません。',
    'after' => ':attribute は :date より後の日付でなければなりません。',
    'alpha' => ':attribute はアルファベットのみを含めることができます。',
    'alpha_dash' => ':attribute はアルファベット、数字、ダッシュ（-）およびアンダースコア（_）を含めることができます。',
    'alpha_num' => ':attribute はアルファベットと数字のみを含めることができます。',
    'array' => ':attribute は配列でなければなりません。',
    'before' => ':attribute は :date より前の日付でなければなりません。',
    'between' => [
        'numeric' => ':attribute は :min と :max の間でなければなりません。',
        'file' => ':attribute は :min KB と :max KB の間でなければなりません。',
        'string' => ':attribute は :min 文字以上 :max 文字以下でなければなりません。',
        'array' => ':attribute は :min と :max の間のアイテム数でなければなりません。',
    ],
    'boolean' => ':attribute は真偽値でなければなりません。',
    'confirmed' => ':attribute の確認が一致しません。',
    'date' => ':attribute は有効な日付ではありません。',
    'date_format' => ':attribute はフォーマット :format と一致しません。',
    'different' => ':attribute と :other は異なっていなければなりません。',
    'digits' => ':attribute は :digits 桁でなければなりません。',
    'digits_between' => ':attribute は :min と :max の間でなければなりません。',
    'email' => ':attribute は有効なメールアドレスでなければなりません。',
    'exists' => '選択された :attribute は無効です。',
    'file' => ':attribute はファイルでなければなりません。',
    'filled' => ':attribute は必須です。',
    'gt' => [
        'numeric' => ':attribute は :value より大きい必要があります。',
        'file' => ':attribute は :value KB より大きい必要があります。',
        'string' => ':attribute は :value 文字より多くなければなりません。',
        'array' => ':attribute は :value より多くのアイテムを持つ必要があります。',
    ],
    'gte' => [
        'numeric' => ':attribute は :value 以上でなければなりません。',
        'file' => ':attribute は :value KB 以上でなければなりません。',
        'string' => ':attribute は :value 文字以上でなければなりません。',
        'array' => ':attribute は :value 個以上のアイテムを持たなければなりません。',
    ],
    'image' => ':attribute は画像でなければなりません。',
    'in' => '選択された :attribute は無効です。',
    'in_array' => ':attribute は :other の中に存在しなければなりません。',
    'integer' => ':attribute は整数でなければなりません。',
    'ip' => ':attribute は有効なIPアドレスでなければなりません。',
    'ipv4' => ':attribute は有効なIPv4アドレスでなければなりません。',
    'ipv6' => ':attribute は有効なIPv6アドレスでなければなりません。',
    'json' => ':attribute は有効なJSON文字列でなければなりません。',
    'lt' => [
        'numeric' => ':attribute は :value より小さい必要があります。',
        'file' => ':attribute は :value KB より小さい必要があります。',
        'string' => ':attribute は :value 文字より少ない必要があります。',
        'array' => ':attribute は :value より少ないアイテムを持つ必要があります。',
    ],
    'lte' => [
        'numeric' => ':attribute は :value 以下でなければなりません。',
        'file' => ':attribute は :value KB 以下でなければなりません。',
        'string' => ':attribute は :value 文字以下でなければなりません。',
        'array' => ':attribute は :value 個以下のアイテムを持たなければなりません。',
    ],
    'max' => [
        'numeric' => ':attribute は :max 以下でなければなりません。',
        'file' => ':attribute は :max KB 以下でなければなりません。',
        'string' => ':attribute は :max 文字以下でなければなりません。',
        'array' => ':attribute は :max 個以下のアイテムを持たなければなりません。',
    ],
    'mimes' => ':attribute は :values タイプのファイルでなければなりません。',
    'mimetypes' => ':attribute は :values タイプのファイルでなければなりません。',
    'min' => [
        'numeric' => ':attribute は少なくとも :min でなければなりません。',
        'file' => ':attribute は少なくとも :min KB でなければなりません。',
        'string' => ':attribute は少なくとも :min 文字でなければなりません。',
        'array' => ':attribute は少なくとも :min 個のアイテムを持たなければなりません。',
    ],
    'not_in' => '選択された :attribute は無効です。',
    'numeric' => ':attribute は数字でなければなりません。',
    'password' => 'パスワードは正しくありません。',
    'present' => ':attribute は存在しなければなりません。',
    'regex' => ':attribute の形式が無効です。',
    'required' => ':attribute は必須です。',
    'required_if' => ':other が :value の場合、:attribute は必須です。',
    'required_unless' => ':other が :value でない限り、:attribute は必須です。',
    'required_with' => ':values が存在する場合、:attribute は必須です。',
    'required_with_all' => ':values が存在する場合、:attribute は必須です。',
    'required_without' => ':values が存在しない場合、:attribute は必須です。',
    'required_without_all' => ':values が存在しない場合、:attribute は必須です。',
    'same' => ':attribute と :other は一致しなければなりません。',
    'size' => [
        'numeric' => ':attribute は :size でなければなりません。',
        'file' => ':attribute は :size KB でなければなりません。',
        'string' => ':attribute は :size 文字でなければなりません。',
        'array' => ':attribute は :size 個のアイテムを持たなければなりません。',
    ],
    'string' => ':attribute は文字列でなければなりません。',
    'timezone' => ':attribute は有効なタイムゾーンでなければなりません。',
    'unique' => ':attribute はすでに存在します。',
    'uploaded' => ':attribute のアップロードに失敗しました。',
    'url' => ':attribute は有効なURLではありません。',
    'uuid' => ':attribute は有効なUUIDでなければなりません。',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
