<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\RequestMeal;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meals = [
            // Breakfast items
            [
                'Name' => 'Oatmeal with Honey & Banana',
                'MealDescription' => 'Rolled oats cooked with skim milk, sliced banana, and a drizzle of honey.',
                'Calories' => 280,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Scrambled Eggs on Wholewheat Toast',
                'MealDescription' => 'Two scrambled eggs on toasted wholewheat bread with a dash of black pepper.',
                'Calories' => 310,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Greek Yogurt Parfait',
                'MealDescription' => 'Low-fat Greek yogurt layered with mixed berries and a spoonful of granola.',
                'Calories' => 240,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Nasi Lemak Biasa (Small Portion)',
                'MealDescription' => 'Coconut milk infused rice with boiled egg, cucumber slices, sambal, and roasted peanuts.',
                'Calories' => 390,
                'grade' => 'B',
                'gred' => 'B',
            ],
            [
                'Name' => 'Roti Canai with Dhal Curry',
                'MealDescription' => 'One piece of traditional flatbread served with protein-rich lentil dhal.',
                'Calories' => 360,
                'grade' => 'B',
                'gred' => 'B',
            ],
            [
                'Name' => 'Tosai with Coconut & Tomato Chutney',
                'MealDescription' => 'Fermented rice and lentil crepe served with light chutneys.',
                'Calories' => 260,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Half-Boiled Eggs with Kaya Toast',
                'MealDescription' => 'Traditional kopitiam breakfast with 2 soft-boiled omega eggs and toast.',
                'Calories' => 340,
                'grade' => 'B',
                'gred' => 'B',
            ],

            // Lunch items
            [
                'Name' => 'Steamed Chicken Rice',
                'MealDescription' => 'Tender poached chicken breast over fragrant rice, served with cucumber slices and ginger sauce.',
                'Calories' => 520,
                'grade' => 'B',
                'gred' => 'B',
            ],
            [
                'Name' => 'Nasi Campur Ayam Bakar & Sayur Bayam',
                'MealDescription' => 'Steamed brown rice with grilled spiced chicken breast and stir-fried spinach.',
                'Calories' => 490,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Mee Sup Ayam (Chicken Noodle Soup)',
                'MealDescription' => 'Clear broth noodles with shredded chicken breast, bok choy, and spring onions.',
                'Calories' => 380,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Nasi Kerabu Ayam Percik',
                'MealDescription' => 'Blue pea flower rice, raw fresh herbs (ulam), and roasted chicken with spiced sauce.',
                'Calories' => 560,
                'grade' => 'B',
                'gred' => 'B',
            ],
            [
                'Name' => 'Grilled Chicken Salad Bowl',
                'MealDescription' => 'Herb-marinated chicken breast over crisp romaine, cherry tomatoes, and olive oil dressing.',
                'Calories' => 350,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Fish Porridge (Bubur Ikan) with Ginger',
                'MealDescription' => 'Warm white fish fillet porridge seasoned with fresh ginger and sesame oil.',
                'Calories' => 310,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Asam Laksa Penang',
                'MealDescription' => 'Spicy, sour mackerel fish broth noodle soup garnished with mint, pineapple, and cucumber.',
                'Calories' => 430,
                'grade' => 'A',
                'gred' => 'A',
            ],

            // Dinner items
            [
                'Name' => 'Tom Yum Goong Soup with White Rice',
                'MealDescription' => 'Zesty lemongrass and prawn soup served with half a bowl of steamed rice.',
                'Calories' => 420,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Grilled Salmon with Steamed Broccoli',
                'MealDescription' => 'Pan-seared Atlantic salmon fillet with steamed broccoli and roasted baby potatoes.',
                'Calories' => 480,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Chapati (2 pcs) with Chicken Keema',
                'MealDescription' => 'Two dry-roasted wholewheat flatbreads with lean minced chicken curry.',
                'Calories' => 440,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Sup Daging Halia with Brown Rice',
                'MealDescription' => 'Clear beef broth with tender beef slices, carrots, potatoes, and ginger.',
                'Calories' => 460,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Yong Tau Foo Soup (5 items)',
                'MealDescription' => 'Stuffed tofu, bittergourd, and okra with fish paste in a light clear soup.',
                'Calories' => 320,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Tahu Tempeh Sambal with Sayur Lodeh',
                'MealDescription' => 'Stir-fried fermented soybean cakes and pressed tofu with vegetables in turmeric broth.',
                'Calories' => 380,
                'grade' => 'A',
                'gred' => 'A',
            ],
            [
                'Name' => 'Bihun Sup Daging (Beef Vermicelli Soup)',
                'MealDescription' => 'Rice vermicelli in clear beef bone broth topped with bean sprouts and celery.',
                'Calories' => 410,
                'grade' => 'A',
                'gred' => 'A',
            ],
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }

        // Seed a few pending meal requests for admin testing
        $pendingRequests = [
            [
                'Name' => 'Kuey Teow Thng (Clear Broth)',
                'MealDescription' => 'Flat rice noodles in duck and pork bone broth with minced chicken and fish balls.',
                'Calories' => 390,
                'grade' => 'A',
            ],
            [
                'Name' => 'Protein Acai Smoothie Bowl',
                'MealDescription' => 'Acai puree blended with whey protein, topped with chia seeds and sliced kiwi.',
                'Calories' => 320,
                'grade' => 'A',
            ],
            [
                'Name' => 'Air-fried Chicken Tender Wrap',
                'MealDescription' => 'Crispy breaded chicken tenders wrapped in a tortilla with lettuce and garlic yogurt sauce.',
                'Calories' => 450,
                'grade' => 'B',
            ],
        ];

        foreach ($pendingRequests as $req) {
            RequestMeal::create($req);
        }
    }
}
