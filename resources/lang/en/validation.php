<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted'             => ':attribute kabul edilmelidir.',
    'active_url'           => ':attribute geçerli bir URL değil.',
    'after'                => ':attribute :date\'den sonraki bir tarih olmalıdır',
    'after_or_equal'       => ':attribute :date\'den sonraki veya ona eşit bir tarih olmalıdır.',
    'alpha'                => ':attribute yalnızca harf içerebilir.',
    'alpha_dash'           => ':attribute, yalnızca harfler, rakamlar ve çizgiler içerebilir.',
    'alpha_num'            => ':attribute sadece harf ve rakam içerebilir.',
    'array'                => ':attribute bir dizi olmalıdır.',
    'before'               => ':attribute, :date tarihinden önce olmalıdır.',
    'before_or_equal'      => ':attribute, :date tarihinden önce veya ona eşit bir tarih olmalıdır.',
    'between'              => [
        'numeric' => ':attribute, :min ve :max arasında olmalıdır.',
        'file'    => ':attribute, :min ve :max kilobayt arasında olmalıdır.',
        'string'  => ':attribute, :min ve :max karakterleri arasında olmalıdır.',
        'array'   => ':attribute, arasında :min ve :max öğeleri olmalıdır.',
    ],
    'boolean'              => ':attribute alanı doğru veya yanlış olmalıdır.',
    'confirmed'            => ':attribute onayı uyuşmuyor.',
    'date'                 => ':attribute geçerli bir tarih değildir.',
    'date_format'          => ':attribute, :format biçimiyle eşleşmiyor.',
    'different'            => ':attribute ve :other farklı olmalıdır.',
    'digits'               => ':attribute, :digits basamaklı olmalıdır.',
    'digits_between'       => ':attribute, :min ve :max basamak arasında olmalıdır.',
    'dimensions'           => ':attribute\'de geçersiz görüntü boyutları var.',
    'distinct'             => ':attribute alanında yinelenen bir değer var.',
    'email'                => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'exists'               => 'Seçilen :attribute geçersiz.',
    'file'                 => ':attribute bir dosya olmalıdır.',
    'filled'               => ':attribute alanının bir değeri olmalıdır.',
    'image'                => ':attribute bir resim olmalıdır.',
    'in'                   => 'Seçilen :attribute geçersiz.',
    'in_array'             => ':attribute alanı :other\'da mevcut değil.',
    'integer'              => ':attribute tamsayı olmalıdır.',
    'ip'                   => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4'                 => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'                 => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json'                 => ':attribute, geçerli bir JSON dizesi olmalıdır.',
    'max'                  => [
        "numeric" => ":attribute değeri :max değerinden küçük olmalıdır.",
        "file"    => ":attribute değeri :max kilobayt değerinden küçük olmalıdır.",
        "string"  => ":attribute değeri :max karakter değerinden küçük olmalıdır.",
        "array"   => ":attribute değeri :max adedinden az nesneye sahip olmalıdır."
    ],
    'mimes'                => ':attribute, aşağıdaki türden bir dosya olmalıdır: :values.',
    'mimetypes'            => ':attribute, aşağıdaki türde bir dosya olmalıdır: :values.',
    'min'                  => [
        "numeric" => ":attribute değeri :min değerinden büyük olmalıdır.",
        "file"    => ":attribute değeri :min kilobayt değerinden büyük olmalıdır.",
        "string"  => ":attribute değeri :min karakter değerinden büyük olmalıdır.",
        "array"   => ":attribute en az :min nesneye sahip olmalıdır."
    ],
    'not_in'               => 'Seçili :attribute geçersiz.',
    'numeric'              => ':attribute rakam olmalıdır.',
    'present'              => ':attribute alanı mevcut olmalıdır.',
    'regex'                => ':attribute biçimi geçersiz.',
    'required'             => ':attribute alanı gereklidir.',
    'required_if'          => ':attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => ':attribute alanı :values varken zorunludur.',
    'required_with_all'    => ':attribute alanı :values varken zorunludur.',
    'required_without'     => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':attribute alanı :values yokken zorunludur.',
    'same'                 => ':attribute ile :other eşleşmelidir.',
    'size'                 => [
        "numeric" => ":attribute :size olmalıdır.",
        "file"    => ":attribute :size kilobyte olmalıdır.",
        "string"  => ":attribute :size karakter olmalıdır.",
        "array"   => ":attribute :size nesneye sahip olmalıdır."
    ],
    'string'               => ':attribute bir dize olmalıdır.',
    'timezone'             => ':attribute geçerli bir bölge olmalıdır.',
    "unique"               => ":attribute daha önceden kayıt edilmiş.",
    'uploaded'             => ':attribute yüklenemedi.',
    "url"                  => ":attribute biçimi geçersiz.",
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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [],
];