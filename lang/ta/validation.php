<?php

// Only the rules this application actually uses are translated here --
// any rule not listed automatically falls back to lang/en/validation.php
// (Laravel resolves missing keys per-line against the fallback locale).

return [

    'required' => ':attribute புலம் அவசியம்.',
    'email' => ':attribute சரியான மின்னஞ்சல் முகவரியாக இருக்க வேண்டும்.',
    'confirmed' => ':attribute உறுதிப்படுத்தல் பொருந்தவில்லை.',
    'unique' => ':attribute ஏற்கனவே பயன்படுத்தப்பட்டுள்ளது.',
    'string' => ':attribute எழுத்துக்களைக் கொண்டிருக்க வேண்டும்.',
    'integer' => ':attribute முழு எண்ணாக இருக்க வேண்டும்.',
    'numeric' => ':attribute எண்ணாக இருக்க வேண்டும்.',
    'boolean' => ':attribute உண்மை அல்லது பொய்யாக இருக்க வேண்டும்.',
    'date' => ':attribute சரியான தேதியாக இருக்க வேண்டும்.',
    'after_or_equal' => ':attribute :date தேதிக்கு சமமாகவோ பிறகோ இருக்க வேண்டும்.',
    'in' => 'தேர்ந்தெடுக்கப்பட்ட :attribute செல்லுபடியாகாது.',
    'exists' => 'தேர்ந்தெடுக்கப்பட்ட :attribute செல்லுபடியாகாது.',
    'image' => ':attribute ஒரு படமாக இருக்க வேண்டும்.',
    'mimes' => ':attribute :values வகை கோப்பாக இருக்க வேண்டும்.',
    'current_password' => 'கடவுச்சொல் தவறானது.',

    'min' => [
        'numeric' => ':attribute குறைந்தது :min ஆக இருக்க வேண்டும்.',
        'string' => ':attribute குறைந்தது :min எழுத்துக்கள் இருக்க வேண்டும்.',
        'file' => ':attribute குறைந்தது :min கிலோபைட்டுகள் இருக்க வேண்டும்.',
        'array' => ':attribute குறைந்தது :min உருப்படிகள் இருக்க வேண்டும்.',
    ],
    'max' => [
        'numeric' => ':attribute அதிகபட்சம் :max ஆக இருக்க வேண்டும்.',
        'string' => ':attribute அதிகபட்சம் :max எழுத்துக்களாக இருக்க வேண்டும்.',
        'file' => ':attribute அதிகபட்சம் :max கிலோபைட்டுகளாக இருக்க வேண்டும்.',
        'array' => ':attribute அதிகபட்சம் :max உருப்படிகளாக இருக்க வேண்டும்.',
    ],
    'between' => [
        'numeric' => ':attribute :min மற்றும் :max இடையே இருக்க வேண்டும்.',
        'string' => ':attribute :min மற்றும் :max எழுத்துக்களுக்கு இடையே இருக்க வேண்டும்.',
        'file' => ':attribute :min மற்றும் :max கிலோபைட்டுகளுக்கு இடையே இருக்க வேண்டும்.',
        'array' => ':attribute :min மற்றும் :max உருப்படிகளுக்கு இடையே இருக்க வேண்டும்.',
    ],

    'attributes' => [
        'name' => 'பெயர்',
        'first_name' => 'முதல் பெயர்',
        'last_name' => 'கடைசி பெயர்',
        'email' => 'மின்னஞ்சல்',
        'password' => 'கடவுச்சொல்',
        'phone' => 'தொலைபேசி எண்',
        'blood_group' => 'இரத்த வகை',
        'district' => 'மாவட்டம்',
        'city' => 'நகரம்',
        'address' => 'முகவரி',
        'date_of_birth' => 'பிறந்த தேதி',
        'weight_kg' => 'எடை',
        'hemoglobin' => 'ஹீமோகுளோபின்',
        'nic' => 'தே.அ. எண்',
        'gender' => 'பாலினம்',
        'units_needed' => 'தேவையான யூனிட்கள்',
        'urgency' => 'அவசரநிலை',
        'appointment_date' => 'சந்திப்பு தேதி',
        'appointment_time' => 'சந்திப்பு நேரம்',
        'registration_id' => 'பதிவு எண்',
    ],

];
