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

    'accepted' => 'O :attribute deve ser be accepted.',
    'active_url' => 'O :attribute não é uma URL válida.',
    'after' => 'O :attribute deve ser uma data após :date.',
    'after_or_equal' => 'O :attribute deve ser um date after ou igual à :date.',
    'alpha' => 'O :attribute deve conter apenas.',
    'alpha_dash' => 'O :attribute deve conter apenas letras, números, traços ou sublinhado.',
    'alpha_num' => 'O :attribute deve conter apenas letras e números.',
    'array' => 'O :attribute deve ser um array.',
    'before' => 'O :attribute deve ser uma data anterior :date.',
    'before_or_equal' => 'O :attribute deve ser uma data anterior ou igual à :date.',
    'between' => [
        'numeric' => 'O :attribute deve estar entre :min e :max.',
        'file' => 'O :attribute deve estar entre :min e :max kilobytes.',
        'string' => 'O :attribute deve estar entre :min e :max caracteres.',
        'array' => 'O :attribute deve estar entre :min e :max itens.',
    ],
    'boolean' => 'O :attribute campo deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação do :attribute não confere.',
    'date' => 'O :attribute não é uma data válida.',
    'date_equals' => 'A :attribute deve ser um igual à :date.',
    'date_format' => 'O :attribute não está no formato :format.',
    'different' => 'O :attribute e :other devem ser diferentes.',
    'digits' => 'O :attribute deve ter :digits digitos.',
    'digits_between' => 'O :attribute deve ser entre :min e :max digitos.',
    'dimensions' => 'O :attribute tem um tamanho de imagem invalido.',
    'distinct' => 'O campo :attribute tem valor já existente.',
    'email' => 'O :attribute deve ser um email válido.',
    'exists' => 'O :attribute selecionado é inválido.',
    'file' => 'O :attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute deve ter um valor.',
    'gt' => [
        'numeric' => 'O :attribute deve ser maior que :value.',
        'file' => 'O :attribute deve ser maior que :value kilobytes.',
        'string' => 'O :attribute deve ser maior que :value caracteres.',
        'array' => 'O :attribute deve ter mais que :value itens.',
    ],
    'gte' => [
        'numeric' => 'O :attribute deve ser maior que ou igual :value.',
        'file' => 'O :attribute deve ser maior que ou igual :value kilobytes.',
        'string' => 'O :attribute deve ser maior que ou igual :value caracteres.',
        'array' => 'O :attribute deve ter :value itens ou mais.',
    ],
    'image' => 'O :attribute deve ser uma imagem.',
    'in' => 'The selected :attribute é inválido.',
    'in_array' => 'O :attribute field does not exist in :other.',
    'integer' => 'O :attribute deve ser um inteiro.',
    'ip' => 'O :attribute deve ser um endereço IP válido.',
    'ipv4' => 'O :attribute deve ser um endereço IPv4 válido.',
    'ipv6' => 'O :attribute deve ser um endereço IPv6 válido.',
    'json' => 'O :attribute deve ser um string JSON válido.',
    'lt' => [
        'numeric' => 'O :attribute deve ser menor que :value.',
        'file' => 'O :attribute deve ser menor que :value kilobytes.',
        'string' => 'O :attribute deve ser menor que :value caracteres.',
        'array' => 'O :attribute deve ter menos que :value itens.',
    ],
    'lte' => [
        'numeric' => 'O :attribute deve ser menor ou igual à :value.',
        'file' => 'O :attribute deve ser menor ou igual à :value kilobytes.',
        'string' => 'O :attribute deve ser menor ou igual à :value caracteres.',
        'array' => 'O :attribute deve ter menos que :value itens.',
    ],
    'max' => [
        'numeric' => 'O :attribute deve ser menor que :max.',
        'file' => 'O :attribute deve ser menor que :max kilobytes.',
        'string' => 'O :attribute deve ser menor que :max caracteres.',
        'array' => 'O :attribute deve ter moenos que :max itens.',
    ],
    'mimes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => 'O :attribute deve ter ao menos :min.',
        'file' => 'O :attribute deve ter ao menos :min kilobytes.',
        'string' => 'O :attribute deve ter ao menos :min caracteres.',
        'array' => 'O :attribute deve ter ao menos :min itens.',
    ],
    'not_in' => 'O :attribute selecionado é inválido.',
    'not_regex' => 'O formato :attribute é inválido.',
    'numeric' => 'O :attribute deve ser um número.',
    'present' => 'O campo :attribute deve estar presente.',
    'regex' => 'O formato :attribute é invalido.',
    'required' => 'O Campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O campo :attribute é obrigaório a menos que :other seja :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está present.',
    'required_without_all' => 'O campo :attribute é obrigatório quando :values não estão presentes.',
    'same' => 'O :attribute e :other deve corresponder.',
    'size' => [
        'numeric' => 'O :attribute deve ter :size.',
        'file' => 'O :attribute deve ter :size kilobytes.',
        'string' => 'O :attribute deve ter :size caracteres.',
        'array' => 'O :attribute deve conter :size itens.',
    ],
    'starts_with' => 'O :attribute deve começar com um destes valores: :values',
    'string' => 'O :attribute deve ser uma string.',
    'timezone' => 'O :attribute deve ser uma timezona válida.',
    'unique' => 'O :attribute jpa existe.',
    'uploaded' => 'O :attribute falhou ao ser enviado.',
    'url' => 'O formato :attribute é inválido.',
    'uuid' => 'O :attribute deve ser um UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages fou attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line fou a given attribute rule.
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
