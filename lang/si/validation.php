<?php

// Only the rules this application actually uses are translated here --
// any rule not listed automatically falls back to lang/en/validation.php
// (Laravel resolves missing keys per-line against the fallback locale).

return [

    'required' => ':attribute ක්ෂේත්‍රය අවශ්‍යයි.',
    'email' => ':attribute වලංගු විද්‍යුත් තැපැල් ලිපිනයක් විය යුතුය.',
    'confirmed' => ':attribute තහවුරු කිරීම නොගැලපේ.',
    'unique' => ':attribute දැනටමත් භාවිතා කර ඇත.',
    'string' => ':attribute අකුරු වලින් සමන්විත විය යුතුය.',
    'integer' => ':attribute සම්පූර්ණ සංඛ්‍යාවක් විය යුතුය.',
    'numeric' => ':attribute සංඛ්‍යාවක් විය යුතුය.',
    'boolean' => ':attribute සත්‍ය හෝ අසත්‍ය විය යුතුය.',
    'date' => ':attribute වලංගු දිනයක් විය යුතුය.',
    'after_or_equal' => ':attribute :date දිනයට සමාන හෝ පසු දිනයක් විය යුතුය.',
    'in' => 'තෝරාගත් :attribute වලංගු නොවේ.',
    'exists' => 'තෝරාගත් :attribute වලංගු නොවේ.',
    'image' => ':attribute රූපයක් විය යුතුය.',
    'mimes' => ':attribute :values වර්ගයේ ගොනුවක් විය යුතුය.',
    'current_password' => 'මුරපදය වැරදිය.',

    'min' => [
        'numeric' => ':attribute අවම වශයෙන් :min විය යුතුය.',
        'string' => ':attribute අවම වශයෙන් අකුරු :min ක් විය යුතුය.',
        'file' => ':attribute අවම වශයෙන් කිලෝබයිට් :min ක් විය යුතුය.',
        'array' => ':attribute අවම වශයෙන් අයිතම :min ක් තිබිය යුතුය.',
    ],
    'max' => [
        'numeric' => ':attribute උපරිම :max විය යුතුය.',
        'string' => ':attribute උපරිම වශයෙන් අකුරු :max ක් විය යුතුය.',
        'file' => ':attribute උපරිම වශයෙන් කිලෝබයිට් :max ක් විය යුතුය.',
        'array' => ':attribute උපරිම වශයෙන් අයිතම :max ක් තිබිය යුතුය.',
    ],
    'between' => [
        'numeric' => ':attribute :min සහ :max අතර විය යුතුය.',
        'string' => ':attribute අකුරු :min සහ :max අතර විය යුතුය.',
        'file' => ':attribute කිලෝබයිට් :min සහ :max අතර විය යුතුය.',
        'array' => ':attribute අයිතම :min සහ :max අතර තිබිය යුතුය.',
    ],

    'attributes' => [
        'name' => 'නම',
        'first_name' => 'මුල් නම',
        'last_name' => 'අවසාන නම',
        'email' => 'විද්‍යුත් තැපෑල',
        'password' => 'මුරපදය',
        'phone' => 'දුරකථන අංකය',
        'blood_group' => 'රුධිර වර්ගය',
        'district' => 'දිස්ත්‍රික්කය',
        'city' => 'නගරය',
        'address' => 'ලිපිනය',
        'date_of_birth' => 'උපන් දිනය',
        'weight_kg' => 'බර',
        'hemoglobin' => 'හීමොග්ලොබින්',
        'nic' => 'ජා.හැ. අංකය',
        'gender' => 'ස්ත්‍රී පුරුෂ භාවය',
        'units_needed' => 'අවශ්‍ය ඒකක',
        'urgency' => 'හදිසි තත්ත්වය',
        'appointment_date' => 'හමුවීමේ දිනය',
        'appointment_time' => 'හමුවීමේ වේලාව',
        'registration_id' => 'ලියාපදිංචි අංකය',
    ],

];
