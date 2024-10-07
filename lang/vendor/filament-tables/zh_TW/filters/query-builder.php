<?php

return [

    'form' => [

        'operator' => [
            'label' => '運算子',
        ],

        'or_groups' => [

            'block' => [
                'label' => '析取（或）',
            ],

        ],

        'number' => [

            'equals' => [

                'label' => [
                    'direct' => '等於',
                    'inverse' => '不等於',
                ],

            ],

            'is_max' => [

                'label' => [
                    'direct' => '是最大值',
                    'inverse' => '大於',
                ],

            ],

            'is_min' => [

                'label' => [
                    'direct' => '是最小值',
                    'inverse' => '小於',
                ],

            ],

            'form' => [

                'number' => [
                    'label' => '數字',
                ],

            ],

        ],

        'text' => [

            'contains' => [

                'label' => [
                    'direct' => '包含',
                    'inverse' => '不包含',
                ],

            ],

            'ends_with' => [

                'label' => [
                    'direct' => '結束於',
                    'inverse' => '不結束於',
                ],

            ],

            'equals' => [

                'label' => [
                    'direct' => '等於',
                    'inverse' => '不等於',
                ],

            ],

            'starts_with' => [

                'label' => [
                    'direct' => '開始於',
                    'inverse' => '不開始於',
                ],

            ],

            'form' => [

                'text' => [
                    'label' => '內容',
                ],

            ],

        ],

    ],

    'actions' => [

        'add_rule' => [
            'label' => '新增規則',
        ],

    ],

];
