<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Knife>
 */
class KnifeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $knives = [
            [
                'name' => 'Chef Damas',
                'category' => 'Couteaux de cuisine',
                'image' => '/images/gallery/DSC_1478.webp',
                'description' => 'Couteau de chef en acier damas avec manche en bois stabilisé.',
                'type' => 'Chef',
                'length' => '20cm',
                'material' => 'Acier Damas',
                'price' => 450.00
            ],
            [
                'name' => 'Pliant Bois Noble',
                'category' => 'Couteaux pliants',
                'image' => '/images/gallery/DSC_1481.webp',
                'description' => 'Couteau pliant avec lame en acier carbone et manche en bois de noyer.',
                'type' => 'Pliant',
                'length' => '12cm',
                'material' => 'Acier Carbone',
                'price' => 280.00
            ],
            [
                'name' => 'Hunter XL',
                'category' => 'Couteaux de chasse',
                'image' => '/images/gallery/DSC_1496.webp',
                'description' => 'Couteau de chasse robuste avec étui en cuir fait main.',
                'type' => 'Chasse',
                'length' => '25cm',
                'material' => 'Acier Inoxydable',
                'price' => 380.00
            ],
            [
                'name' => 'Santoku Intégral',
                'category' => 'Couteaux de cuisine',
                'image' => '/images/gallery/DSC_1509-HDR.webp',
                'description' => 'Santoku à lame intégrale avec rivets en laiton.',
                'type' => 'Santoku',
                'length' => '18cm',
                'material' => 'Acier Inoxydable',
                'price' => 320.00
            ],
            [
                'name' => 'Damas 256 Couches',
                'category' => 'Damas',
                'image' => '/images/gallery/DSC_1542.webp',
                'description' => 'Lame en acier damas 256 couches, motif feather.',
                'type' => 'Damas',
                'length' => '22cm',
                'material' => 'Acier Damas 256 couches',
                'price' => 650.00
            ],
            [
                'name' => 'Office Ébène',
                'category' => 'Couteaux de cuisine',
                'image' => '/images/gallery/DSC_1583.webp',
                'description' => "Couteau d'office avec manche en ébène et acier inoxydable.",
                'type' => 'Office',
                'length' => '10cm',
                'material' => 'Acier Inoxydable',
                'price' => 180.00
            ],
            [
                'name' => 'Gentleman Folder',
                'category' => 'Couteaux pliants',
                'image' => '/images/gallery/DSC_1964.webp',
                'description' => 'Couteau pliant élégant pour un usage quotidien.',
                'type' => 'Gentleman',
                'length' => '11cm',
                'material' => 'Acier Inoxydable',
                'price' => 220.00
            ],
            [
                'name' => 'Bushcraft',
                'category' => 'Couteaux de chasse',
                'image' => '/images/gallery/DSC_2001.webp',
                'description' => 'Couteau de survie robuste et polyvalent.',
                'type' => 'Bushcraft',
                'length' => '24cm',
                'material' => 'Acier Carbone',
                'price' => 350.00
            ],
            [
                'name' => 'ElassaStyle',
                'category' => 'Damas',
                'image' => '/images/gallery/ElassaStyle.webp',
                'description' => 'Motif torsadé complexe sur cette lame en acier damas.',
                'type' => 'Damas',
                'length' => '19cm',
                'material' => 'Acier Damas Torsadé',
                'price' => 580.00
            ]
        ];

        return $this->faker->randomElement($knives);
    }

    /**
     * Créer un couteau spécifique par son index
     */
    public function specific(int $index): Factory
    {
        $knives = [
            [
                'name' => 'Chef Damas',
                'category' => 'Couteaux de cuisine',
                'image' => '/images/gallery/DSC_1478.webp',
                'description' => 'Couteau de chef en acier damas avec manche en bois stabilisé.',
                'type' => 'Chef',
                'length' => '20cm',
                'material' => 'Acier Damas',
                'price' => 450.00
            ],
            [
                'name' => 'Pliant Bois Noble',
                'category' => 'Couteaux pliants',
                'image' => '/images/gallery/DSC_1481.webp',
                'description' => 'Couteau pliant avec lame en acier carbone et manche en bois de noyer.',
                'type' => 'Pliant',
                'length' => '12cm',
                'material' => 'Acier Carbone',
                'price' => 280.00
            ],
            [
                'name' => 'Hunter XL',
                'category' => 'Couteaux de chasse',
                'image' => '/images/gallery/DSC_1496.webp',
                'description' => 'Couteau de chasse robuste avec étui en cuir fait main.',
                'type' => 'Chasse',
                'length' => '25cm',
                'material' => 'Acier Inoxydable',
                'price' => 380.00
            ],
            [
                'name' => 'Santoku Intégral',
                'category' => 'Couteaux de cuisine',
                'image' => '/images/gallery/DSC_1509-HDR.webp',
                'description' => 'Santoku à lame intégrale avec rivets en laiton.',
                'type' => 'Santoku',
                'length' => '18cm',
                'material' => 'Acier Inoxydable',
                'price' => 320.00
            ],
            [
                'name' => 'Damas 256 Couches',
                'category' => 'Damas',
                'image' => '/images/gallery/DSC_1542.webp',
                'description' => 'Lame en acier damas 256 couches, motif feather.',
                'type' => 'Damas',
                'length' => '22cm',
                'material' => 'Acier Damas 256 couches',
                'price' => 650.00
            ],
            [
                'name' => 'Office Ébène',
                'category' => 'Couteaux de cuisine',
                'image' => '/images/gallery/DSC_1583.webp',
                'description' => "Couteau d'office avec manche en ébène et acier inoxydable.",
                'type' => 'Office',
                'length' => '10cm',
                'material' => 'Acier Inoxydable',
                'price' => 180.00
            ],
            [
                'name' => 'Gentleman Folder',
                'category' => 'Couteaux pliants',
                'image' => '/images/gallery/DSC_1964.webp',
                'description' => 'Couteau pliant élégant pour un usage quotidien.',
                'type' => 'Gentleman',
                'length' => '11cm',
                'material' => 'Acier Inoxydable',
                'price' => 220.00
            ],
            [
                'name' => 'Bushcraft',
                'category' => 'Couteaux de chasse',
                'image' => '/images/gallery/DSC_2001.webp',
                'description' => 'Couteau de survie robuste et polyvalent.',
                'type' => 'Bushcraft',
                'length' => '24cm',
                'material' => 'Acier Carbone',
                'price' => 350.00
            ],
            [
                'name' => 'ElassaStyle',
                'category' => 'Damas',
                'image' => '/images/gallery/ElassaStyle.webp',
                'description' => 'Motif torsadé complexe sur cette lame en acier damas.',
                'type' => 'Damas',
                'length' => '19cm',
                'material' => 'Acier Damas Torsadé',
                'price' => 580.00
            ]
        ];

        return $this->state(fn() => $knives[$index] ?? $knives[0]);
    }
}
